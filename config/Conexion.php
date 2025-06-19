<?php
session_start();
class Conexion
{
    private $conexion;
    public function __construct()
    {
        try {
            $this->conexion = new PDO("mysql:host=127.0.0.1;dbname=ekolabs;charset=utf8mb4", "root", "");
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Error de conexion: " . $e->getMessage();
            die();
        }
    }

    protected function get_conexion()
    {
        return $this->conexion;
    }
}
