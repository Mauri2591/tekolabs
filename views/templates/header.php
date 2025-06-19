<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="<?php echo URL ?>/public/images/imagen.png" type="image/png">
    <link rel="stylesheet" href="<?php echo URL ?>/public/css/bootstrap5.0.2.css?sheet=<?php echo rand() ?>">
    <link rel="stylesheet" href="<?php echo URL ?>/public/css/styleAdminGestion.css?sheet=<?php echo rand() ?>">
    <link rel="stylesheet" href="<?php echo URL ?>/public/css/datatables2.3.1.css?sheet=<?php echo rand() ?>">
    <link rel="stylesheet" href="<?php echo URL ?>/public/css/iconsBootstrap.css?sheet=<?php echo rand() ?>">
</head>

<nav class="navbar navbar-expand-lg" id="nav_admin_gestion">
    <div class="container-fluid">
        <!-- Botón para colapsar el menú en pantallas pequeñas (opcional) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h6><span class="badge bg-secondary border border-danger mx-1">Sesion : <span
                    class="badge bg-secondary text-light border border-danger mx-1"><?php echo $_SESSION['nombre'] ?></span>
            </span>
        </h6>

        <div class="collapse navbar-collapse mx-5" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Gestion
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?php echo URL ?>/views/Home/Admin/Gestion/">Desafios</a>
                        </li>
                        <li><a class="dropdown-item" target="_blank"
                                href="<?php echo URL ?>/views/Home/Admin/Gestion/nuevoChallenge.php">Nuevo Challenge</a>
                        </li>
                        <li><a class="dropdown-item" href="#">Usuarios</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Mi Perfil</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page"
                        href="<?php echo URL ?>/views/Home/Admin/Logout/">Salir</a>
                </li>

            </ul>
        </div>
    </div>
</nav>