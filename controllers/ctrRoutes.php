<?php

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($request === "/tekoLabs/") {
    include_once __DIR__ . "/../views/Home/index.php";
} else if ($request === "/tekoLabs/admin/" || $request === "/tekoLabs/admin") {
    include_once __DIR__ . "/../views/Home/Admin/index.php";
} else {
    include_once __DIR__ . "/../views/Home/index.php";
}
