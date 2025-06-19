<?php
require_once __DIR__ . "/../config/Conexion.php";
require_once __DIR__ . "/../config/Config.php";
require_once __DIR__ . "/../models/Desafios.php";
require_once __DIR__ . "/../models/Openssl.php";

$desafio = new Desafios();
switch ($_GET['case']) {

    case 'get_chellenges_pagina_x_desafio_id':
        $desafio = new Desafios();
        $datos = $desafio->get_chellenges_pagina_x_desafio_id($_POST['id']);
        echo json_encode([
            "leyenda" => $datos->leyenda ?? null
        ]);
        break;

    case 'get_vulnerabilidad_challenge_x_id':
        $datos = $desafio->get_vulnerabilidad_challenge_x_id($_POST['id']);
        if (!empty($datos->archivo_php)) {
            $archivo = __DIR__ . "../../Desafios/VulnsCtf/" . basename($datos->archivo_php);
            if (file_exists($archivo)) {
                include $archivo;
            } else {
                echo "<div style='color:red;'>Archivo no encontrado: $archivo</div>";
            }
        } else {
            if (!$datos || !$datos->solucion) {
                echo "SIN DATOS, CARGUE UN CHALLENGE";
            } else {
                echo '<script>const SOLUCION = "' . $datos->solucion . '";</script>';
                echo $datos->descripcion;
            }
        }
        break;

    case 'get_desafio_x_id':
        $datos = $desafio->get_desafio_x_id($_POST['id']);
        switch ($datos->nivel) {
            case 'DIFICIL':
?>
                <span class="badge text-danger border border-danger" style="font-size: 1rem;"><?php echo strtoupper($datos->nombre) ?>
                    -
                    NIVEL
                    <?php echo strtoupper($datos->nivel) ?></span>
            <?php break;
            case 'MEDIO':
            ?>
                <span class="badge text-warning border border-warning" style="font-size: 1rem;"><?php echo strtoupper($datos->nombre) ?>
                    -
                    NIVEL
                    <?php echo strtoupper($datos->nivel) ?></span>
            <?php break;
            case 'FACIL':
            ?>
                <span class="badge text-success border border-success" style="font-size: 1rem;"><?php echo strtoupper($datos->nombre) ?>
                    -
                    NIVEL
                    <?php echo strtoupper($datos->nivel) ?></span>
<?php break;
            default:
                # code...
                break;
        }
        break;

    case 'update_estados_desafios':
        $desafio->update_estados_desafios($_POST['id'], $_POST['id_evento']);
        break;

    case 'get_solucion_vulnerabilidad_challenge_x_id':
        echo json_encode($desafio->get_solucion_vulnerabilidad_challenge_x_id($_POST['id']));
        break;

    case 'get_nivel_desafio':
        echo json_encode($desafio->get_nivel_desafio($_POST['id']));
        break;

    case 'update_estado_esafio':
        $data = $desafio->update_estado_esafio($_POST['id'], $_POST['id_estado']);
        echo json_encode(["Status" => "Success"]);
        http_response_code(200);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Case inválido']);
        break;

    case 'generar_solucion_aleatoria':
        require_once "../../tekoLabs/Desafios/VulnsCtf/InformationDisclousureEnv.php";
        $env = new InformationDisclousureEnv();
        $solucion = $env->generar_y_guardar_solucion_random($_POST['id']);
        echo json_encode(["solucion" => $solucion]);
        break;

    case 'get_ultimo_valor_version_paginas_eventos':
        echo json_encode($desafio->get_ultimo_valor_version_paginas_eventos());
        break;

    case 'inserar_usuario_desafio':
        $usuario = $_POST['usuario'] ?? null;
        $id_desafio = (int) $_POST['id_desafio'];
        $id_version = isset($_POST['id_version_paginas_eventos']) ? (int) $_POST['id_version_paginas_eventos'] : null;
        $resolvio = $_POST['resolvio'] ?? null;

        if (!empty($usuario)) {
            $usuario = htmlspecialchars($usuario, ENT_QUOTES);
            $desafio->inserar_usuario_desafio($usuario, $id_desafio, $id_version, $resolvio);

            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "message" => "Usuario insertado correctamente"
            ]);
        } else {
            // Si el correo es null o vacío, NO insertamos nada.
            http_response_code(204); // No Content
        }
        break;


    case 'get_ultimo_desafio_activo':
        echo json_encode($desafio->get_ultimo_desafio_activo($_POST['id']));
        break;

    case 'reiniciar_desafios_y_version':
        echo json_encode($desafio->reiniciar_desafios_y_version($_POST['id_desafio']));
        break;
}
