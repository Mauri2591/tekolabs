<?php
require_once __DIR__ . "/../../../../config/Conexion.php";
require_once __DIR__ . "/../../../../config/Config.php";
if (isset($_SESSION['id'])) {

    include_once __DIR__ . "/../../../templates/header.php";
?>
    </nav>

    <body>

        <div class="row">
            <div class="col-lg-12" id="cont_tabla_desafios">
                <div class="container bg-light mt-2">
                    <div class="container-fluid">
                        <p class="text-center"><span class="badge bg-success text-light border border-dark">ALTA DE
                                EVENTOS</span>
                        </p>
                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                            data-bs-target="#modalAltaChallenge">
                            Nuevo
                        </button>
                        <table id="table_get_eventos" class="table table-striped"
                            style="text-align: center; align-items: center;">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">N°</th>
                                    <th style="text-align: center;">EVENTO</th>
                                    <th style="text-align: center;">IMAGEN</th>
                                    <th style="text-align: center;">FECHA EVENTO</th>
                                    <th style="text-align: center;">GENEROS</th>
                                    <th style="text-align: center;">GESTION</th>
                                    <th style="text-align: center;"></th>
                                </tr>
                            </thead>
                        </table>


                    </div>
                </div>
            </div>
            <?php include_once __DIR__ . "/mdlAltaDesafio.php"; ?>
        </div>
    <?php
    include_once __DIR__ . "/../../../templates/body.php";
} else {
    header("Location:" . URL . "/admin/");
    exit;
}
    ?>
    <script src="<?php echo URL ?>/views/Home/Admin/Gestion/main.js?sheet=<?php echo rand() ?>"></script>