  <?php if (isset($_GET['ch'])) {
        require_once __DIR__ . "/../config/Conexion.php";
        require_once __DIR__ . "/../config/Config.php";
        require_once __DIR__ . "/../models/Openssl.php";
        require_once __DIR__ . "/../models/Paginas.php";
        require_once __DIR__ . "/../models/Desafios.php";
        $id_desafio = Openssl::param_decrypt($_GET['ch']);
    ?>
      <!DOCTYPE html>
      <html lang="en">

      <head>
          <meta charset="UTF-8">
          <title>Desafío</title>
          <link rel="icon" href="<?php echo URL ?>/public/images/imagen.png" type="image/png">
          <link rel="stylesheet" href="<?php echo URL ?>/public/css/bootstrap5.0.2.css?sheet=<?php echo rand(); ?>">
          <link rel="stylesheet" href="<?php echo URL ?>/Desafios/css/style.css?sheet=<?php echo rand(); ?>">
          <script src="<?php echo URL ?>/public/js/jquery.js"></script>

          <input type="hidden" hidden id="id_desafio_hidden">

          <script>
              const ID_DESAFIO = <?php echo intval($id_desafio); ?>;
              document.getElementById("id_desafio_hidden").value = ID_DESAFIO
              $.post("../controllers/ctrDesafios.php?case=get_vulnerabilidad_challenge_x_id", {
                      id: ID_DESAFIO
                  },
                  function(data, textStatus, jqXHR) {

                  },
                  "json"
              );
          </script>
      </head>

      <body>
          <div class="container">
              <div class="d-flex justify-content-between align-items-center mt-4">
                  <h3 class="text-light pb-2 mb-0">
                      Desafío <span id="desafio_cabecera"> </span>
                  </h3>
                  <div style="color: orange; font-family: monospace; font-size: 1rem;">
                      Tiempo restante: <span id="reloj_regresivo">00</span> segundos
                  </div>
              </div>

              <!-- Terminal NARRATIVA -->
              <div class="terminal terminal-narrativa">
                  <div class="row">
                      <div class="prompt" style="color: yellowgreen; font-weight: bold;">Nota:</div>
                      <div id="cont_leyenda_challenge">

                      </div>
                  </div>
              </div>

              <!-- Terminal DESAFÍO -->
              <div class="terminal terminal-challenge">
                  root@telecom_ctf:~# <br>


                  <?php
                    // $ruta_html = __DIR__ . "/../html_challenges/desafio_" . intval($id_desafio) . ".html";
                    if (($id_desafio)) {
                    ?>
                      <section style="text-align: center;" class="contenedor_challenges_desafios">
                      </section>
                  <?php
                    } else {
                        echo '<div class="prompt">Challenge no encontrado para el ID proporcionado.</div>';
                    }
                    ?>
                  <div style="display: flex; justify-content: end; margin-top: auto; margin-bottom: 0; margin-right: 1rem;">
                      <button id="btnCancelarChallenge" type="button" class="btn btn-outline-danger py-0">Cancelar</button>
                  </div>
              </div>
          </div>
          <script src="<?php echo URL ?>/public/js/bootstrap5.0.2.js"></script>
          <script src="<?php echo URL ?>/public/js/sweetalert2@11.js"></script>
          <script src="<?php echo URL ?>/Desafios/js/main.js?sheet=<?php echo rand(); ?>" defer></script>
      </body>

      </html>
  <?php } else {
        header("Location:" . URL);
    } ?>