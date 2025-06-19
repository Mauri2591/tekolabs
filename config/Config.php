<?php
require_once __DIR__ . "/Conexion.php";
define("URL", "http://127.0.0.1/tekoLabs");
class Config extends Conexion
{
    public function get_vulnerabilidad_challenge_x_id($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT vulnerabilidades_o_tematicas.id,
                       vulnerabilidades_o_tematicas.nombre,
                       vulnerabilidades_o_tematicas.descripcion,
                       vulnerabilidades_o_tematicas.archivo_php,
                       vulnerabilidades_o_tematicas.solucion,
                       vulnerabilidades_o_tematicas.ayuda,
                       vulnerabilidades_o_tematicas.cve
                FROM vulnerabilidades_o_tematicas
                INNER JOIN desafios ON vulnerabilidades_o_tematicas.id = desafios.id_vulnerabilidad_o_tematica
                WHERE desafios.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
