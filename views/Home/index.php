<?php
require_once __DIR__ . "/../../config/Config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo URL ?>/public/images/imagen.png" type="image/png">
    <title>tekoLabs</title>
    <link rel="stylesheet" href="<?php echo URL ?>/public/css/style.css?sheet=<?php echo rand() ?>">
</head>

<body>

    <section id="cont_audio_pagina">
    </section>

    <section id="section_logos_teco_eko">

        <img width="125" height="50" src="<?php echo URL ?>/public/images/imagen.png" alt="">

        <span id="imagen_evento">

        </span>
    </section>
    <section id="section_titulo">
        <h1 id="titulo_inicio">BIENVENIDO AL DESAFIO</h1>
        <p class="parrafo_inicio">Estas listo?</p>
    </section>


    <section id="section_combo_categoria">
        <p id="parrafo_inicio_combo" class="parrafo_inicio">Elige un genero y comienza</p>
        <select name="" id="combo_genero">

        </select>
    </section>

    <section id="cont_challenge_titulo">
        <span id="titulo_challenge"></span>
    </section>

    <div id="cont_section_combo_categoria">

        <section id="section_cont_sitios">
            <h1 style="color:lime; background:black; font-family: monospace; text-align:center; padding: 1rem;">
                <marquee scrollamount="8">
                    &lt;/&gt; [ access granted ] :: root@telecom_ctf ~# ./iniciar_reto.sh
                </marquee>
            </h1>

        </section>
    </div>
    <script src="<?php echo URL ?>/public/js/jquery.js?sheet=<?php echo rand(); ?>"></script>
    <script src="<?php echo URL ?>/public/js/main.js?sheet=<?php echo rand(); ?>"></script>
    <script src="<?php echo URL ?>/public/js/sweetalert2@11.js?sheet=<?php echo rand(); ?>"></script>
    <script>
        // Elimina el reloj de cuenta regresiva anterior
        localStorage.removeItem("expira_desafio");
    </script>

</body>

</html>