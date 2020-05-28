<?php

namespace App;

use PDO;

class ConexionSQL
{
    private static $instance;
    private $host = '127.0.0.1';
    private $db = 'B4nC0';
    private $usuario = 'B4nC0';
    private $contra = '';
    private $conexion;
    private $seInsertoTodo;

    /**
     * @return mixed
     */
    public function getSeInsertoTodo()
    {
        return $this->seInsertoTodo;
    }

    /**
     * @param mixed $seInsertoTodo
     */
    public function setSeInsertoTodo($seInsertoTodo): void
    {
        $this->seInsertoTodo = $seInsertoTodo;
    }



    /**
     * ConsultaSQL constructor.
     */
    private function __construct()
    {
        $this->crearConexion();
    }
    public function cambiarConexion($nombreBD)
    {
        $this->conexion=null;
        $this->db=$nombreBD;
        $this->crearConexion();
    }
    private function crearConexion()
    {
        $dsn="pgsql:host=".$this->host.";"."port=5432;dbname=".$this->db.";"."user=".$this->usuario.";"."password=".$this->contra.";"."charset=utf8";
        try
        {
            $this->conexion=new PDO($dsn);
            $this->conexion->exec("set names utf8");
        }
        catch (PDOException $e)
        {
            throw $e;
        }
    }
    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getDb(): string
    {
        return $this->db;
    }

    /**
     * @param string $db
     */
    public function setDb(string $db): void
    {
        $this->db = $db;
    }

    /**
     * @return string
     */
    public function getUsuario(): string
    {
        return $this->usuario;
    }

    /**
     * @param string $usuario
     */
    public function setUsuario(string $usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return string
     */
    public function getContra(): string
    {
        return $this->contra;
    }

    /**
     * @param string $contra
     */
    public function setContra(string $contra): void
    {
        $this->contra = $contra;
    }

    /**
     * @return PDO
     */
    public function getConexion(): PDO
    {
        return $this->conexion;
    }

    /**
     * @param PDO $conexion
     */
    public function setConexion(PDO $conexion): void
    {
        $this->conexion = $conexion;
    }
}
