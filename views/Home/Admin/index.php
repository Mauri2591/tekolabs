<?php

use Soap\Url;

require_once __DIR__ . "/../../../config/Config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tekoLabs</title>
    <link rel="stylesheet" href="<?php echo URL ?>/public/css/style.css?sheet=<?php echo rand() ?>">
</head>

<body>
    <?php echo $_SERVER['REQUEST_URI'] ?>
    <?php
    if (isset($_GET) && isset($_GET['mje'])) {
        switch ($_GET['mje']) {
            case 'vacio':

                break;
            case 'error':

                break;

            default:
                # code...
                break;
        }
    }
    ?>
    <section id="section_logos_teco_eko">
        <h4 id="texto_telecom">T e l e c o m
        </h4>
        <img src="<?php echo URL ?>/public/images/logoEko.webp" alt="logo de la eko" width="60" height="60">
        <ul class="ul_admin">
            <li>
                <a class="a_admin" href="<?php echo URL ?>">IR A LABORATORIO</a>
            </li>
        </ul>
    </section>

    <!-- <nav class="nav_ul_admin">
        <ul class="ul_admin">
            <li>
            </li>
        </ul>
    </nav> -->


    <section id="section_titulo">
        <h1 id="titulo_inicio">ADMIN</h1>
    </section>
    <div id="cont_form_admin_login">
        <div id="cont_form_login_admin">
            <form id="form_login_admin" action="<?php echo URL ?>/controllers/ctrUsuariosGestion.php?case=login_admin"
                method="post">
                <div class="grupo_form">
                    <label class="label_panel_admin" for="usuario">USUARIO</label>
                    <input class="input_panel_admin" type="text" name="usuario" id="usuario">
                </div>
                <div class="grupo_form">
                    <label class="label_panel_admin" for="password">PASSWORD</label>
                    <input class="input_panel_admin" type="password" name="password" id="password">
                </div>
                <button id="btn_ingresa_panel_admin" type="submit">Ingresar</button>
            </form>

        </div>

    </div>

    <script src="<?php echo URL ?>/public/js/jquery.js?sheet=<?php echo rand(); ?>"></script>
    <script src="<?php echo URL ?>/views/Home/Admin/main.js?sheet=<?php echo rand(); ?>"></script>

</body>

</html>