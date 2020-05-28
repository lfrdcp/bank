<?php

namespace App;


class Nota
{
    private $id;
    private $name;
    private $details;
    private $start;
    private $end;
    private $color;
    private $hora;
    private $id_cliente;
    private $folioGen;
    private $tipo;

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }


    /**
     * @return mixed
     */
    public function getFolioGen()
    {
        return $this->folioGen;
    }

    /**
     * @param mixed $folioGen
     */
    public function setFolioGen($folioGen): void
    {
        $this->folioGen = $folioGen;
    }

    /**
     * @return mixed
     */
    public function getIdCliente()
    {
        return $this->id_cliente;
    }

    /**
     * @param mixed $id_cliente
     */
    public function setIdCliente($id_cliente): void
    {
        $this->id_cliente = $id_cliente;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @param mixed $hora
     */
    public function setHora($hora): void
    {
        $this->hora = $hora;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color): void
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start): void
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end): void
    {
        $this->end = $end;
    }

    /**
     * @return mixed
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param mixed $details
     */
    public function setDetails($details): void
    {
        $this->details = $details;
    }

    public function __construct($tipo, $folioGen, $id_cliente, $id, $nombre, $fecha_hora, $comentario)
    {
        $this->tipo = $tipo;
        $this->folioGen = $folioGen;
        $this->id_cliente = $id_cliente;
        $this->id = $id;
        $this->name = $nombre;
        $this->start = substr($fecha_hora, 0, 16);
        $this->end = substr($fecha_hora, 0, 16);
        $this->hora = substr($fecha_hora, 11, 5);
        $this->details = $comentario;
        $this->asignarColor();
    }

    private function asignarColor()
    {
        if ($this->tipo == "Gestion") $this->color = 'light-blue accent-2';

        if ($this->tipo == "Contactar") $this->color = 'deep-purple accent-2';

        if ($this->tipo == "L-Pendiente") $this->color = 'yellow lighten-1';
        if ($this->tipo == "L-Realizada") $this->color = 'lime darken-1';

        if ($this->tipo == "P-Pendiente") $this->color = 'orange lighten-1';
        if ($this->tipo == "P-Realizado") $this->color = 'light-green darken-1';
        if ($this->tipo == "P-Cancelado" || $this->tipo == "L-Cancelado" ) $this->color = 'red darken-1';

        if ($this->tipo == "Convenio-Activo") $this->color = 'blue accent-2';
        if ($this->tipo == "Convenio-Cancelado") $this->color = 'blue darken-4';
        if ($this->tipo == "Convenio-Pendiente") $this->color = 'orange lighten-2';


        if ($this->tipo == "Intencion") $this->color = 'pink lighten-1';
    }
}
