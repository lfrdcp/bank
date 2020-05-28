<?php

namespace App\Traits;

use App\Nota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait GestionTrait
{
    public static function generarNotas($nota, $tipo)
    {
        $notas = [];
        for ($i = 0; $i < count($nota); $i++) {
            $id_cliente = $nota[$i]->id_cliente;
            $nombre = $nota[$i]->nombre;
            if ($tipo == "Gestion") {
                $id = $nota[$i]->id_gestion;
                $fecha = $nota[$i]->created_at;
                $comentario = '';
                $folioGen = $nota[$i]->folioGen;
            } else if ($tipo == "Contactar") {
                $id = $nota[$i]->id_gestion;
                $fecha = $nota[$i]->fecha_hora_contactar;
                $comentario = $nota[$i]->comentario;
                $folioGen = '';

            } else if ($tipo == "Intencion") {
                $id = $nota[$i]->id_gestion;
                $fecha = $nota[$i]->fecha;
                $comentario = '';
                $folioGen = $nota[$i]->folioGen;
            } else if ($tipo == "Convenio") {

                if (is_null($nota[$i]->convenio_estado)) {
                    $tipoNuevo = "Convenio-Pendiente";
                } else if (!$nota[$i]->convenio_estado) {
                    $tipoNuevo = "Convenio-Cancelado";
                } else {
                    $tipoNuevo = "Convenio-Activo";
                }

                $id = $nota[$i]->id_convenio;
                $folioGen = $nota[$i]->folioGen;
                $fecha = $nota[$i]->created_at;
                $comentario = '';
            } else if ($tipo == "CalendarioPagos") {
                $id = $nota[$i]->id_calendario;
                $folioGen = $nota[$i]->folioGen;
                $fecha = $nota[$i]->fecha_pago_esperada;
                $comentario = $nota[$i]->comentario;
                switch ($nota[$i]->folioGen[0]) {
                    case 'L':
                        if (!$nota[$i]->convenio_estado) {
                            $tipoNuevo = "L-Cancelado";
                        } else {
                            if (is_null($nota[$i]->pagado)) {
                                $tipoNuevo = "L-Pendiente";
                            } else {
                                $tipoNuevo = "L-Realizada";
                            }
                        }
                        break;
                    case 'C':

                        if ($nota[$i]->convenio_estado == TRUE) {
                            if ($nota[$i]->pagado == TRUE) {
                                $tipoNuevo = "P-Realizado";
                            } else {
                                $tipoNuevo = "P-Pendiente";
                            }
                        } else if (is_null($nota[$i]->convenio_estado)) {
                            $tipoNuevo = "P-Pendiente";
                        } else {
                            $tipoNuevo = "P-Cancelado";
                        }

                        break;
                }
            }


            if ($tipo == "CalendarioPagos" || $tipo == "Convenio") {
                $notas[$i] = new Nota($tipoNuevo, $folioGen, $id_cliente, $id, $nombre, $fecha, $comentario);
            } else {
                $notas[$i] = new Nota($tipo, $folioGen, $id_cliente, $id, $nombre, $fecha, $comentario);
            }
        }
        return $notas;
    }

    public
    static function obtenerGestionesSuperYAdmin()
    {
        return DB::SELECT('
            SELECT DISTINCT ON ("Gestion".id_cliente) "Gestion".id_cliente,
			"Gestion".*,"Cliente".nombre 
			FROM "Gestion" INNER JOIN "Cliente" ON "Gestion".id_cliente = "Cliente".id_cliente
            WHERE migrado is null
            ORDER BY "Gestion".id_cliente, "Gestion".id_gestion desc ');
    }

    public
    static function obtenerGestionesGestorNotificacion($id_usuario, $fecha)
    {
        return DB::SELECT('SELECT "Gestion".*,"Cliente".nombre 
			FROM "Gestion" INNER JOIN "Cliente" ON "Gestion".id_cliente = "Cliente".id_cliente
            WHERE "Gestion".id_usuario = ? 
            AND substr(concat("Gestion".fecha_hora_contactar),1,10) = ?
            ORDER BY "Gestion".created_at ASC 
        ', [$id_usuario, $fecha]);
    }

    public
    static function obtenerGestionesGestor($id_usuario)
    {
        return DB::SELECT('SELECT DISTINCT ON ("Gestion".id_cliente) "Gestion".id_cliente,
			"Gestion".*,"Cliente".nombre 
			FROM "Gestion" INNER JOIN "Cliente" ON "Gestion".id_cliente = "Cliente".id_cliente
            WHERE "Gestion".id_usuario = ?
            ORDER BY "Gestion".id_cliente, "Gestion".id_gestion desc 
        ', [$id_usuario]);
    }

    public
    static function obtenerGestiones($id_usuario, $id_cliente)
    {
        return DB::SELECT('SELECT * FROM "Gestion" WHERE id_usuario = ? AND id_cliente = ?', [$id_usuario, $id_cliente]);
    }

    public
    static function obtenerGestionesSinCliente($id_usuario)
    {
        return DB::SELECT('SELECT * FROM "Gestion" WHERE id_usuario = ?', [$id_usuario]);
    }

    public
    static function obtenerGestionesTodo()
    {
        return DB::SELECT('SELECT * FROM "Gestion"');
    }

    public
    static function obtenerDatosCalendario($id_cliente)
    {
        return DB::SELECT('SELECT "Convenio".convenio_estado, "Gestion"."folioGen","CalendarioPagos".comentario,"CalendarioPagos".id_calendario, "CalendarioPagos".pagado,
substr(concat(\'\',"CalendarioPagos".fecha_pago_esperada),0,17)
FROM "Gestion" INNER JOIN "Convenio" 
ON "Gestion".id_gestion="Convenio".id_gestion 
INNER JOIN "CalendarioPagos" ON "CalendarioPagos".folio = "Convenio".id_convenio 
where "Gestion"."folioGen" IS NOT NULL AND "CalendarioPagos".id_cliente = ?', [$id_cliente]);
    }

}
