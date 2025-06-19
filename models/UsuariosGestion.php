<?php
class UsuariosGestion extends Conexion
{
    public function login_admin($usuario, $password)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT * FROM usuarios_gestion WHERE usuario = :usuario AND id_estados = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':usuario', htmlspecialchars($usuario, ENT_QUOTES), PDO::PARAM_STR);
        $stmt->execute();
        $resul = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resul && password_verify($password, $resul['password'])) {
            $_SESSION['id'] = $resul['id'];
            $_SESSION['usuario'] = $resul['usuario'];
            $_SESSION['nombre'] = $resul['nombre'];
            return true;
        }
        return false;
    }
}
