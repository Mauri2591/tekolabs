<?php
require_once __DIR__ . "/../config/Conexion.php";
require_once __DIR__ . "/../config/Config.php";
require_once __DIR__ . "/../models/Paginas.php";
require_once __DIR__ . "/../models/Openssl.php";
$pagina = new Paginas();

switch ($_GET['case']) {

    case 'get_audio_pagina':
        $datos = $pagina->get_audio_pagina();
        foreach ($datos as $val) {
?>
            <audio id="audio_pagina_<?php echo strtoupper($val['soundtrack']) ?>" loop
                data-genero="<?php echo $val['id_genero'] ?>">
                <source src="<?php echo URL ?>/public/eventos/<?php echo $val['soundtrack'] ?>" type="audio/mpeg">
            </audio>
        <?php
        }
        break;


    case 'get_datos_combo_generos_evento_pagina_inicio':
        $datos = $pagina->get_datos_combo_generos_evento_pagina_inicio();
        ?>
        <option value="0">SELECCIONE</option>
        <?php foreach ($datos as $val): ?>
            <option value="<?php echo $val['id_pagina'] ?>" data-genero="<?php echo $val['id_genero'] ?>">
                <?php echo htmlspecialchars($val['genero']) ?>
            </option>
        <?php endforeach; ?>
        <?php
        break;

    case 'get_chellenges_pagina':
        $idPagina = $_POST['id'];
        $data = $pagina->get_chellenges_pagina($idPagina);

        // NUEVO: verificar si hay desafíos en juego en el evento completo
        $hay_jugando = $pagina->hay_desafio_jugando_en_evento($idPagina);

        ob_start(); // Capturar HTML
        foreach ($data as $val) {
            $nivel = strtolower(trim($val['nivel']));
            $clase = "cont_imagenes_laboratorios_inicio_$nivel";
            $clase_span = "span_imagenes_laboratorios_inicio_$nivel";
        ?>
            <div class="<?php echo $clase ?>">
                <img src="<?php echo URL ?>/public/eventos/challenges/<?php echo $val['imagen'] ?>" alt="logo del desafío"
                    width="200" height="100"><br>

                <?php
                switch ($val['id_estado']) {
                    case '0':
                        echo '<a href="#" style="text-decoration: line-through;pointer-events: none;font-weight: bold;">RESUELTO</a>';
                        break;

                    case '1':
                        if ($hay_jugando) {
                            echo '<a style="font-weight: bold" href="#" title="Completa el desafío actual para continuar">BLOQUEADO</a>';
                        } else {
                            echo '<a style="font-weight: bold" onclick=updateEstadoDesafio(' . $val['id_desafio'] . ') href="' . URL . '/Desafios/?ch=' . Openssl::param_encrypt($val['id_desafio']) . '">IR</a>';
                        }
                        break;

                    case '2':
                        echo '<a style="border:.1rem solid #fff; color:#fff; padding: 0 .2rem; font-weight: bold" onclick=updateEstadoDesafio(' . $val['id_desafio'] . ') href="' . URL . '/Desafios/?ch=' . Openssl::param_encrypt($val['id_desafio']) . '">JUGANDO</a>';
                        break;

                    case '3':
                        echo '<a style="pointer-events: none; font-weight: bold" href="#">BLOQUEADO</a>';
                        break;

                    default:
                        echo '<a href="#">SIN DESAFÍO</a>';
                        break;
                }
                ?>
                <br>
                <span class="<?php echo $clase_span ?>"><?php echo ucfirst($nivel) ?></span>
            </div>
        <?php
        }
        $html = ob_get_clean();
        echo json_encode([
            "titulo" => $data[0]['nombre'],
            "html" => $html
        ]);
        break;

    case 'insert_imagen_challenge':
        if (isset($_FILES['imagen']) && !empty($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $nombre_original = $_FILES['imagen']['name'];
            $tmp = $_FILES['imagen']['tmp_name'];
            $ext = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
            $hash = sha1_file($tmp);
            $nombre_imagen = $hash . '.' . $ext;

            $carpeta_destino = __DIR__ . "../../public/eventos/challenges";

            if (!file_exists($carpeta_destino)) {
                mkdir($carpeta_destino, 0755, true);
            }

            move_uploaded_file($tmp, "$carpeta_destino/$nombre_imagen");

            $pagina->insert_imagen_challenge($nombre_imagen, $_POST['id_desafio']);
        } else {
            var_dump($_FILES['imagen']);
        }
        break;

    case 'get_desafios_tabla_gestion':
        $datos = $pagina->get_desafios_tabla_gestion();
        $data = array();
        foreach ($datos as $row) {
            // Saltar fila si el usuario es ANONIMUS
            if ($row['usuario'] == "ANONIMUS") {
                continue;
            }
            $sub_array = array();
            $sub_array[] = '<span class="badge border border-secondary text-dark bg-light">' . $row['usuario'] . '</span>';
            $sub_array[] = '<span class="badge border border-dark text-light bg-info">' . $row['cantidad_participaciones'] . '</span>';
            $sub_array[] = $row['cantidad_resueltos'] > 0 ? '<span class="badge border border-dark text-light bg-info">' . $row['cantidad_resueltos'] . '</span>' : '<span class="badge border border-dark text-dark bg-info">' . $row['cantidad_resueltos'] . '</span>';
            $sub_array[] = '<span class="badge bg-light text-dark">' . date('d-m-Y H:i:s', strtotime($row['ultima_participacion'])) . '</span>';
            $data[] = $sub_array;
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;


    case 'get_cantidad_version_paginas_eventos':
        $dato = $pagina->get_cantidad_version_paginas_eventos($_POST['id_estado']);
        ?>
        <span
            style="border: .1px solid red; padding: 0 1rem; border-radius: 1rem; color:red;font-weight: bold;background-color:rgb(230, 230, 230);">Ronda
            <?php echo ($dato->total + 1) ?></span>
    <?php
        break;

    case 'get_datos_icono_evento_pagina_inicio':
        $datos = $pagina->get_datos_icono_evento_pagina_inicio();

        if ($datos && isset($datos->imagen)) {
            echo json_encode([
                "estado" => "activo",
                "html" => '<img src="' . URL . '/public/eventos/' . $datos->carpeta_imagen . '/' . $datos->imagen . '" alt="logo de la eko" width="60" height="60">'
            ]);
        } else {
            echo json_encode([
                "estado" => "inactivo",
                "html" => '<span style="color:red; border-radius: .5rem; border: .1rem solid red; font-size: .8rem; padding: 0 .3rem;">SIN EVENTO</span>'
            ]);
        }
        break;

    case 'insert_evento':
        $evento = $_POST['evento'];
        $fecha_evento = $_POST['fecha_evento'];

        if (empty($evento) || empty($fecha_evento) || empty($_FILES['imagen'])) {
            echo json_encode(["Error" => "Datos vacíos"]);
            http_response_code(400);
            exit;
        }

        if (
            isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0
        ) {
            // Procesar imagen
            $nombre_original = $_FILES['imagen']['name'];
            $tmp = $_FILES['imagen']['tmp_name'];
            $ext = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
            $hash = sha1_file($tmp); // <== SE GENERA ACÁ
            $nombre_imagen = $hash . '.' . $ext;

            // Crear carpeta
            $nombre_carpeta = $hash;
            $destino_base = __DIR__ . "../../public/eventos/$nombre_carpeta";
            if (!file_exists($destino_base)) {
                mkdir($destino_base, 0755, true);
            }

            // Mover archivo
            move_uploaded_file($tmp, "$destino_base/$nombre_imagen");

            // Insertar en la base
            $pagina->insert_evento($evento, $nombre_carpeta, $nombre_imagen, $fecha_evento);

            echo json_encode(["status" => "ok", "msg" => "Evento insertado correctamente"]);
        } else {
            echo json_encode(["status" => "error", "msg" => "Error al subir archivos"]);
            http_response_code(500);
        }
        break;

    case 'update_leyenda_challenge':
        if (!empty($_POST['leyenda'])) {
            $pagina->update_leyenda_challenge($_POST['id'], $_POST['leyenda']);
        } else {
            echo json_encode(["Error" => "Datos vacios"]);
            http_response_code(400);
        }
        break;

    case 'get_nombre_evento_activo':
        $datos = $pagina->get_nombre_evento_activo();
    ?>
        <span><?php echo $datos->evento ?></span>
    <?php
        break;

    case 'get_desafios_grafico_gestion':
        echo json_encode($pagina->get_desafios_grafico_gestion());
        break;

    case 'get_eventos':
        $datos = $pagina->get_eventos(); // eventos
        $datos_generos = $pagina->get_combo_categorias(); // generos/categorías
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = '<span class="badge border border-dark bg-danger text-light">' . $row['id'] . '</span>';
            // EVENTO
            $sub_array[] = '<span class="badge bg-warning border border-dark text-dark">' . htmlspecialchars($row['evento']) . '</span>';
            // IMAGEN
            $sub_array[] = '<img src="' . URL . '/public/eventos/' . $row['carpeta_imagen'] . '/' . $row['imagen'] . '" alt="" width="50" height="50">';
            // FECHA
            $sub_array[] = '<span class="badge bg-light border border-dark text-dark">' . date('d-m-Y', strtotime($row['fecha_evento'])) . '</span>';
            $generos_usados = $pagina->get_generos_usados_por_evento($row['id']);
            $select = '<select class="form-select form-select-sm w-75 mx-auto" id="select_genero_' . $row['id'] . '"';
            $select .= (count($generos_usados) >= count($datos_generos)) ? ' disabled>' : '>';
            foreach ($datos_generos as $genero) {
                if (!in_array($genero['id'], $generos_usados)) {
                    $select .= '<option value="' . $genero['id'] . '">' . htmlspecialchars($genero['genero']) . '</option>';
                }
            }
            $select .= '</select>';
            $sub_array[] = $select;
            $sub_array[] = $row['id_estado'] == 1
                ? '<span type="button" onclick="cambiarEstadoEvento(' . $row['id'] . ',' . $row['id_estado'] . ')" class="badge bg-primary border border-dark text-light">ACTIVO</span>'
                : '<span type="button" onclick="cambiarEstadoEvento(' . $row['id'] . ',' . $row['id_estado'] . ')" class="badge border bg-secondary border-dark text-dark">INACTIVO</span>';
            // BOTÓN CREAR habilitado o deshabilitado según géneros disponibles
            $crear_btn = '';
            if (count($generos_usados) < count($datos_generos)) {
                $crear_btn = '<button type="button" title="Alta de challenge" onclick="alta_pagina(' . $row['id'] . ', document.getElementById(\'select_genero_' . $row['id'] . '\').value)" class="btn btn-sm btn-outline-primary">Crear</button>';
            } else {
                $crear_btn = '<button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Sin géneros disponibles">Crear</button>';
            }
            $sub_array[] = '
        <div class="btn-group" role="group" aria-label="Acciones">
            <button type="button" onclick="eliminar_evento(' . $row['id'] . ')" class="btn btn-sm btn-outline-danger">Borrar</button>
            ' . $crear_btn . '
            <button type="button" onclick="editar_challenge(' . $row['id'] . ', document.getElementById(\'select_genero_' . $row['id'] . '\').value)" class="btn btn-sm btn-outline-dark">Ver</button>
        </div>';

            $data[] = $sub_array;
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;


    case 'update_evento_activo_inactivo':
        $pagina->update_evento_activo_inactivo($_POST['id'], $_POST['id_estado']);
        break;

    case 'get_count_total_id_estados_eventos':
        echo json_encode($pagina->get_count_total_id_estados_eventos());
        break;

    case 'delete_evento':
        $pagina->delete_evento($_POST['id']);
        break;

    case 'select_combo_generos_pagina':
        $datos = $pagina->select_combo_generos_pagina();
        break;

    case 'insert_pagina_challenge':
        if (isset($_FILES['soundtrack']) && $_FILES['soundtrack']['error'] === 0) {
            // Crear carpeta
            $nombre_carpeta = $hash;
            $destino_base = realpath(__DIR__ . "../..") . "/public/eventos/$nombre_carpeta";
            if (!file_exists($destino_base)) {
                mkdir($destino_base, 0755, true);
            }

            // Procesar audio
            $soundtrack_nombre_original = $_FILES['soundtrack']['name'];
            $soundtrack_tmp = $_FILES['soundtrack']['tmp_name'];
            $soundtrack_ext = strtolower(pathinfo($soundtrack_nombre_original, PATHINFO_EXTENSION));
            $soundtrack_hash = sha1_file($soundtrack_tmp);
            $soundtrack_nombre = $soundtrack_hash . '.' . $soundtrack_ext;

            move_uploaded_file($soundtrack_tmp, "$destino_base/$soundtrack_nombre");

            $pagina->insert_pagina_challenge(
                $_POST['nombre'],
                $_POST['personaje_principal'],
                $_POST['personaje_secundario'],
                $_POST['id_evento'],
                $_POST['id_genero'],
                $soundtrack_nombre,
                $_POST['id_estado']
            );
        }

        break;

    case 'get_leyenda_desafio':
        echo json_encode($pagina->get_leyenda_desafio($_POST['id']));
        break;

    case 'get_desafios_y_paginas':
        $id_evento = $_POST['id'];
        $datos = $pagina->get_desafios_y_paginas($id_evento);
        $data = array();

        foreach ($datos as $row) {
            $sub_array = array();

            // FUNCION DE AYUDA
            $wrap = fn($txt) => '<span class="badge border border-dark text-dark bg-warning" style="word-break: break-word; max-width: 200px; display: inline-block;">' . wordwrap(htmlspecialchars($txt), 15, '<br>', true) . '</span>';

            $sub_array[] = $wrap(strtoupper($row['pagina_nombre']));
            $sub_array[] = '<span class="badge border border-dark text-light" style="background-color:gray">'
                . wordwrap(htmlspecialchars(strtoupper($row['pagina_personaje_principal'])), 15, '<br>', true)
                . '</span>';

            $sub_array[] = '<span class="badge border border-dark text-light" style="background-color:gray">'
                . wordwrap(htmlspecialchars(strtoupper($row['pagina_personaje_secundario'])), 15, '<br>', true)
                . '</span>';

            switch ($row['desafio_nivel']) {
                case '1':
                    $sub_array[] = '<span class="badge border bg-light border-light text-success">' . $row['nivel'] . '</span>';
                    break;
                case '2':
                    $sub_array[] = '<span class="badge border bg-light border-light text-warning">' . $row['nivel'] . '</span>';
                    break;
                case '3':
                    $sub_array[] = '<span class="badge border bg-light border-light text-danger">' . $row['nivel'] . '</span>';
                    break;
                default:
                    $sub_array[] = "Sin datos";
                    break;
            }

            $sub_array[] = '<span class="badge border border-dark text-dark">' . $row['genero'] . '</span>';

            switch ($row['modalidad']) {
                case 'TRIVIA':
                    $sub_array[] = '<span class="badge bg-dark border border-warning text-light">' . $row['modalidad'] . '</span>';
                    break;
                case 'CTF':
                    $sub_array[] = '<span class="badge border border-danger bg-dark text-light">' . $row['modalidad'] . '</span>';

                    break;
                default:
                    $sub_array[] = null;
                    break;
            }
            switch ($row['modalidad']) {
                case 'TRIVIA':
                    $sub_array[] = '<span class="badge bg-dark border border-warning text-light">'
                        . wordwrap(htmlspecialchars(strtoupper($row['nombre_vulnerabilidad'])), 15, '<br>', true)
                        . '</span>';
                    break;
                case 'CTF':
                    $sub_array[] = '<span class="badge border border-danger bg-dark text-light">'
                        . wordwrap(htmlspecialchars(strtoupper($row['nombre_vulnerabilidad'])), 15, '<br>', true)
                        . '</span>';
                    break;
                default:
                    $sub_array[] = null;
                    break;
            }
            switch ($row['id_estado_desafio']) {
                case '0':
                    $sub_array[] = $row['desafio_imagen'] == null
                        ? '<p title="Campo obligatorio" style="color:red">Sin imagen</p>'
                        : '<img src="' . URL . '/public/eventos/challenges/' . $row['desafio_imagen'] . '" width="100" height="50">';

                    $sub_array[] = '<span title="Challenge Finalizado" class="badge bg-success text-light"><i class="bi bi-card-image"></i></span>'
                        . " "
                        . '<span title="Challenge Finalizado" class="badge bg-info text-light"><i class="bi bi-pencil-fill"></i></span><br>'
                        . '<span title="Challenge Finalizado" class="badge bg-secondary text-light"><i class="bi bi-arrow-down-up"></i></span>'
                        . " "
                        . '<span title="Challenge Finalizado" class="badge bg-dark text-light"><i class="bi bi-code-square"></i></span>';
                    break;

                case '1':
                    $sub_array[] = $row['desafio_imagen'] == null
                        ? '<p title="Campo obligatorio" style="color:red">Sin imagen</p>'
                        : '<img src="' . URL . '/public/eventos/challenges/' . $row['desafio_imagen'] . '" width="100" height="50">';

                    $sub_array[] = '<span title="Actualizar Imagen" type="button" onclick="actualizarChallenge(' . $row['id_desafio'] . ')" class="badge bg-success text-light"><i class="bi bi-card-image"></i></span>'
                        . " "
                        . '<span title="Actualizar Leyenda" type="button" onclick="actualizarChallengeLeyenda(' . $row['id_desafio'] . ')" class="badge bg-info text-light"><i class="bi bi-pencil-fill"></i></span><br>'
                        . '<span title="Actualizar Genero y Nombre" type="button" onclick="actualizarChallengeGenero('
                        . $row['id_desafio'] . ', '
                        . $id_evento . ', '
                        . $row['id_genero'] . ',' . $row['id_pagina'] . ')" class="badge bg-secondary text-light"><i class="bi bi-arrow-down-up"></i></span>'
                        . " "
                        . '<span title="Actualizar Vulnerabilidad" type="button" onclick="actualizarChallengeChallenge(' . $row['id_desafio'] . ', ' . $row['id_nivel'] . ')" class="badge bg-dark text-light"><i class="bi bi-code-square"></i></span>';
                    break;

                case '2':
                    $sub_array[] = $row['desafio_imagen'] == null
                        ? '<p title="Campo obligatorio" style="color:red">Sin imagen</p>'
                        : '<img src="' . URL . '/public/eventos/challenges/' . $row['desafio_imagen'] . '" width="100" height="50">';

                    $sub_array[] = '<span title="Challenge Jugando" class="badge bg-success text-light"><i class="bi bi-card-image"></i></span>'
                        . " "
                        . '<span title="Challenge Jugando" class="badge bg-info text-light"><i class="bi bi-pencil-fill"></i></span><br>'
                        . '<span title="Challenge Jugando" class="badge bg-secondary text-light"><i class="bi bi-arrow-down-up"></i></span>'
                        . " "
                        . '<span title="Challenge Jugando" class="badge bg-dark text-light"><i class="bi bi-code-square"></i></span>';
                    break;

                case '3':
                    $sub_array[] = $row['desafio_imagen'] == null
                        ? '<p title="Campo obligatorio" style="color:red">Sin imagen</p>'
                        : '<img src="' . URL . '/public/eventos/challenges/' . $row['desafio_imagen'] . '" width="100" height="50">';

                    $sub_array[] = '<span title="Challenge Bloqueado" class="badge bg-success text-light"><i class="bi bi-card-image"></i></span>'
                        . " "
                        . '<span title="Challenge Bloqueado" class="badge bg-info text-light"><i class="bi bi-pencil-fill"></i></span><br>'
                        . '<span title="Challenge Bloqueado" class="badge bg-secondary text-light"><i class="bi bi-arrow-down-up"></i></span>'
                        . " "
                        . '<span title="Challenge Bloqueado" class="badge bg-dark text-light"><i class="bi bi-code-square"></i></span>';
                    break;

                default:
                    $sub_array[] = $row['desafio_imagen'] == null
                        ? '<p title="Campo obligatorio" style="color:red">Sin imagen</p>'
                        : '<img src="' . URL . '/public/eventos/challenges/' . $row['desafio_imagen'] . '" width="100" height="50">';

                    $sub_array[] = '<span title="Imagen" type="button" onclick="actualizarChallenge(' . $row['id_desafio'] . ')" class="badge bg-success text-light"><i class="bi bi-card-image"></i></span>'
                        . " "
                        . '<span title="Leyenda" type="button" onclick="actualizarChallengeLeyenda(' . $row['id_desafio'] . ')" class="badge bg-info text-light"><i class="bi bi-pencil-fill"></i></span><br>'
                        . '<span title="Genero y Nombre" type="button" onclick="actualizarChallengeGenero('
                        . $row['id_desafio'] . ', '
                        . $id_evento . ', '
                        . $row['id_genero'] . ',' . $row['id_pagina'] . ')" class="badge bg-secondary text-light"><i class="bi bi-arrow-down-up"></i></span>'
                        . " "
                        . '<span title="Challenge" type="button" onclick="actualizarChallengeChallenge(' . $row['id_desafio'] . ', ' . $row['id_nivel'] . ')" class="badge bg-dark text-light"><i class="bi bi-code-square"></i></span>';
                    break;
            }
            $data[] = $sub_array;
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'get_datos_desafio_x_id':
        $datos = $pagina->get_datos_desafio_x_id($_POST['id']);
    ?>
        <p style="text-align: justify;">
        <p name="leyenda_chat_gpt" readonly id="leyenda_chat_gpt" class="form-control form-control-sm mx-1" rows="10" autofocus
            placeholder="Consultele este Prompt a Chat GPT" cols="10">
            Hola chat gpt, como estas.
            Te comento, estoy gestionando un sistema de <span style="font-weight: bold;">Challenges de Hacking Ethico</span> de
            tipo modalidad <span style="font-weight: bold;">TRIVIA y CTF</span>.
            En este caso la modalidad es <span style="font-weight: bold;"><?php echo $datos->modalidad ?></span> y la
            ambientacion es sobre el genero <span style="font-weight: bold;"><?php echo $datos->genero ?></span>.
            El nombre de la tematica es <span style="font-weight: bold;"><?php echo $datos->nombre ?></span>.
            El personaje principal es <span style="font-weight: bold;"><?php echo $datos->personaje_principal ?></span>.
            El secundario es
            <span
                style="font-weight: bold;"><?php echo $datos->personaje_secundario == null ? "EN ESTE CASO NO POSEE PERSONAJE SECUNDARIO" : $datos->personaje_secundario ?></span>.
            El nivel de dificultad es <span style="font-weight: bold;"><?php echo $datos->nivel ?></span> y el nombre del
            challenge a resolver es:
            <span style="font-weight: bold;"><?php echo $datos->nombre_vulnerabilidad ?></span>.
            Lo que te pido es que me armes una breve historia de no mas de 5 renglones para que el usuario interprete bien
            la historia con el challenge a resolver pero sin poner o hacerle una pregunta al usuario ya que eso se lo hago yo y
            tampoco hagas alusion a <span style="font-weight: bold;"><?php echo $datos->nombre_vulnerabilidad ?></span> pero si
            tiene que estar ligada a
            <span style="font-weight: bold;"><?php echo $datos->nombre_vulnerabilidad ?></span>.
        </p>
        </p>
    <?php
        break;

    case 'get_nombre_pagina':
        echo json_encode($pagina->get_nombre_pagina($_POST['id']));
        break;

    case 'update_insertar_vulnerabilidad_challenge':
        $pagina->update_insertar_vulnerabilidad_challenge($_POST['id_vulnerabilidad_o_tematica'], $_POST['id']);
        echo json_encode(["status" => "ok"]);
        break;

    case 'get_modalidades':
        $datos = $pagina->get_modalidades();
    ?>
        <?php foreach ($datos as $key => $val): ?>
            <option value="<?php echo $val['id'] ?>"><?php echo $val['modalidad'] ?></option>
        <?php endforeach; ?>
    <?php
        break;

    case 'get_vulnerabilidad':
        $datos = $pagina->get_vulnerabilidad($_POST['id_modalidad'], $_POST['id_nivel']);
    ?>
        <?php foreach ($datos as $key => $val): ?>
            <option selected title="<?php echo $val['nombre'] ?>" value="<?php echo $val['id_vulnerabilidad'] ?>">
                <?php echo $val['nombre'] ?></option>
        <?php endforeach; ?>
<?php
        break;

    case 'get_generos_por_evento_y_desafio':
        $id_evento = $_POST['id_evento'];
        $id_desafio = $_POST['id_desafio'];
        $datos = $pagina->get_generos_por_evento_y_desafio($id_evento, $id_desafio);
        echo json_encode($datos);
        break;

    case 'update_genero_challenge':
        if (!empty($_POST['id_desafio']) && !empty($_POST['id_genero'])) {
            $pagina->update_genero_challenge($_POST['id_desafio'], $_POST['id_genero'], $_POST['nombre']);
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "error", "msg" => "Datos incompletos"]);
            http_response_code(400);
        }
        break;

    default:
        # code...
        break;
}
