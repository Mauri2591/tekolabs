<?php
require_once __DIR__ . "/../../../../config/Conexion.php";
require_once __DIR__ . "/../../../../config/Config.php";
session_destroy();
header("Location:" . URL . "/admin/");
exit;
