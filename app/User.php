<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'session_id', 'despacho', 'tipo', 'name', 'last_name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function verificarPagoControlador($despachoUsuario)
    {
        $despacho = DB::SELECT(/** @lang text */ 'SELECT pago FROM "Despacho" WHERE nombre = ?', [$despachoUsuario]);
        if (empty($despacho)) {
            return true;
        } else {
            return $despacho[0]->pago;
        }

    }

    public function verificarPago()
    {
        $despacho = DB::SELECT(/** @lang text */ 'SELECT pago FROM "Despacho" WHERE nombre = ?', [$this->despacho]);
        if (empty($despacho)) {
            return true;
        } else {
            return $despacho[0]->pago;
        }
    }

    public function tipoSuperAdmin()
    {
        return $this->tipo === 'Superadministrador';
    }

    public function tipoAdmin()
    {
        if ($this->tipo === 'Administrador') {
            return true;
        } else {
            return false;
        }
    }

    public function tipoSuper()
    {
        if ($this->tipo === 'Supervisor' || $this->tipo === 'Administrador') {
            return true;
        } else {
            return false;
        }
    }

    public function tipoGest()
    {
        if ($this->tipo === 'Gestor' || $this->tipo === 'Supervisor' || $this->tipo === 'Administrador') {
            return true;
        } else {
            return false;
        }
    }

    public static function obtenerUsuariosGestores($despacho)
    {
        return DB::SELECT(/** @lang text */ 'SELECT id,name,last_name,tipo,username FROM "users" WHERE  despacho = ? ORDER BY tipo ASC', [$despacho]);
    }

    public static function obtenerUsuariosPorDespacho()
    {
        return DB::SELECT(/** @lang text */ 'SELECT * FROM "users" WHERE despacho = ?', [auth()->user()->despacho]);
    }

    public static function obtenerUsuariosTodos()
    {
        return DB::SELECT(/** @lang text */ 'SELECT * FROM "users"');
    }

    public static function obtenerUsuarioNombre($name)
    {
        return DB::SELECT(/** @lang text */ 'SELECT * FROM "users" WHERE name = ?', [$name]);
    }

    public static function obtenerUsuarioNombrePorDespacho($name)
    {
        return DB::SELECT(/** @lang text */ 'SELECT * FROM "users" WHERE name = ? AND despacho = ?', [$name, auth()->user()->despacho]);
    }

    public static function obtenerUsuarioDespacho($despacho)
    {
        return DB::SELECT(/** @lang text */ 'SELECT * FROM "users" WHERE despacho = ?', [$despacho]);
    }


    public static function obtenerUsuarioUsername($username)
    {
        return DB::SELECT(/** @lang text */ 'SELECT * FROM "users" WHERE username = ?', [$username]);
    }

    public static function obtenerUsuarioUsernamePorDespacho($username)
    {
        return DB::SELECT(/** @lang text */ 'SELECT * FROM "users" WHERE username = ? AND despacho = ?', [$username, auth()->user()->despacho]);
    }

}
