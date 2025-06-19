<?php
require_once __DIR__ . "/../config/Conexion.php";
require_once __DIR__ . "/../config/Config.php";
var_dump(URL);
exit;
session_destroy();

header("Location: http://127.0.0.1/tekoLabs/");
exit();
