<?php
require_once __DIR__ . "/../../../../config/Conexion.php";
require_once __DIR__ . "/../../../../config/Config.php";
if (isset($_SESSION['id'])) {

    include_once __DIR__ . "/../../../templates/header.php";
?>
    </nav>

    <body>
        <div class="row mt-1">
            <div class="col-12 text-center">
                <span class="badge bg-primary border border-light" style="font-size: 1rem; margin-top: .3rem;">EVENTO
                    <span id="nombre_evento"></span> </span>
            </div>
            <div class="col-lg-6" id="cont_tabla_desafios">

                <div class="d-flex justify-content-start">
                    <button onclick="sorteo()" class="mx-5 mt-2 btn btn-sm"
                        style="background-color:orangered;color:aliceblue; border:2px solid brown">Sorteo</button>
                </div>

                <div class="container bg-light mt-5">
                    <div class="container-fluid">

                        <table id="table_desafios">
                            <thead>
                                <!-- <div class="text-center" id="cantidad_version_paginas_eventos"></div> -->

                                <tr>
                                    <!-- <th style="text-align: center;">RETO</th> -->
                                    <th style="text-align: center; background-color: black; color:aliceblue">JUGADOR
                                    </th>
                                    <th style="text-align: center; background-color: black; color:aliceblue">JUGADOS
                                    </th>
                                    <th style="text-align: center; background-color: black; color:aliceblue">GANADOS
                                    </th>
                                    <th style="text-align: center; background-color: black; color:aliceblue">ULTIMO
                                        JUGADO
                                    </th>
                                    <!-- <th>ESTADO</th> -->

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <!-- <td></td> -->
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td></td> -->

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" style="padding-top: 7rem;" id="cont_char_desafios">
                <div>
                    <canvas id="grafico_usuarios_finalizadores_retos"></canvas>
                </div>
            </div>
        </div>
        <?php include_once __DIR__ . "/mdlSorteo.php"; ?>
    <?php
    include_once __DIR__ . "/../../../templates/body.php";
} else {
    header("Location:" . URL . "/admin/");
    exit;
}
    ?>
    <script src="<?php echo URL ?>/views/Home/Admin/Gestion/main.js?sheet=<?php echo rand() ?>"></script>