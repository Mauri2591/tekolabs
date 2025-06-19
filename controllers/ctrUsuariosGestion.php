<?php
require_once __DIR__ . "/../config/Conexion.php";
require_once __DIR__ . "/../config/Config.php";
require_once __DIR__ . "/../models/UsuariosGestion.php";
$usuario_gestion = new UsuariosGestion();

switch ($_GET['case']) {
    case 'login_admin':
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';

        $login = $usuario_gestion->login_admin($usuario, $password);

        if ($login) {
            header("Location:" . URL . "/views/Home/Admin/Gestion/");
        } else {
            header("Location:" . URL . "/admin/?mje=error");
        }
        exit;
        break;
}
