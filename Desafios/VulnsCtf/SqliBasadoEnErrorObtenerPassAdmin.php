<?php
require_once __DIR__ . "/../../config/Conexion.php";
class SqliBasadoEnErrorObtenerPassAdmin extends Conexion
{
    public function get_usuarios($entrada)
    {
        $entrada = $_POST['rol'] ?? '5';
        $conn = parent::get_conexion();
        $sql = "SELECT * FROM users_ctf_vulnerable_2 WHERE id=$entrada"; // SIN comillas
        try {
            return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<pre style='color:red;'>[ERROR SQL] " . htmlentities($e->getMessage()) . "</pre>";
            return [];
        }
    }

    public function get_vulnerabilidad_challenge_x_id($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT vulnerabilidades_o_tematicas.id,
                       vulnerabilidades_o_tematicas.nombre,
                       vulnerabilidades_o_tematicas.descripcion,
                        vulnerabilidades_o_tematicas.ayuda,
                       vulnerabilidades_o_tematicas.archivo_php,
                       vulnerabilidades_o_tematicas.solucion,
                       vulnerabilidades_o_tematicas.cve
                FROM vulnerabilidades_o_tematicas
                INNER JOIN desafios ON vulnerabilidades_o_tematicas.id = desafios.id_vulnerabilidad_o_tematica
                WHERE desafios.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}



// Variables iniciales
$entrada = $_POST['rol'] ?? '5'; // 👈 esta es la variable que necesitás globalmente
$id_desafio = $_POST['id'] ?? null;

$sqli = new SqliBasadoEnErrorObtenerPassAdmin();
$descripcion = $id_desafio ? $sqli->get_vulnerabilidad_challenge_x_id($id_desafio) : null;

//Mostrar solución si es correcta
if (strtolower(trim($entrada)) === 'e10adc3949ba59abbe56e057f20f883e') {
?>
    <script>
        $.post("../controllers/ctrDesafios.php?case=update_estados_desafios", {
                id: ID_DESAFIO,
                id_estado: 1
            },
            function(data, textStatus, jqXHR) {

            },
            "json"
        );

        clearInterval(intervalo);
        localStorage.removeItem("expira_desafio");

        $.ajax({
            type: "POST",
            url: "../controllers/ctrDesafios.php?case=update_estado_esafio",
            data: {
                id: "<?php echo $id_desafio ?>",
                id_estado: 1 //antes id_estado=0
            },
            dataType: "json",
            success: function(response) {
                Swal.fire({
                    title: 'Felicitaciones!',
                    text: 'Si lo deseas, ingresá tu correo electrónico para rankearte',
                    input: 'email',
                    inputPlaceholder: 'ejemplo@correo.com',
                    confirmButtonText: 'Enviar',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        if (!value) {
                            return '¡Debés ingresar un correo!';
                        }
                    }
                }).then((result) => {
                    let email = (result.isConfirmed && result.value) ? result.value : "ANONIMUS";

                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Correo recibido',
                            text: `Gracias por participar`,
                            timer: 2500,
                            showConfirmButton: false
                        });
                    }

                    $.post("../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos",
                        function(data) {
                            let ID_VERSION_PAGINAS_EVENTOS = data.id;

                            $.post("../controllers/ctrDesafios.php?case=inserar_usuario_desafio", {
                                usuario: email,
                                id_desafio: ID_DESAFIO,
                                id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS,
                                resolvio: "si"
                            }, function() {
                                setTimeout(() => {
                                    window.location.replace('../sessionDestroy.php');
                                }, 2500);
                            }, "json");
                        }, "json");
                });
            },
            error: function(err) {
                console.log(err);
            }
        });
    </script>";
<?php
    $datos = [];
} else {
    $datos = $sqli->get_usuarios($entrada);
}
?>

<!-- ✅ DESCRIPCIÓN DEL DESAFÍO (estilo consola) -->
<?php if ($descripcion): ?>
    <span style="color: #33ff33; font-family: monospace;">
        <?= nl2br(htmlspecialchars($descripcion->descripcion)) ?>
    </span> <i class="text-light" title="<?php echo $descripcion->ayuda ?>">[?]</i>
    <br><br>
<?php endif; ?>


<!-- ✅ RESULTADOS SQL -->
<div style="width: 100%; border:1px solid gray; background-color:rgb(90, 90, 90); margin: 0 auto;">
    CUENTAS DEL SISTEMA
    <?php foreach ($datos as $val): ?>
        <p style="color: #33ff33; font-family: monospace;">
            USUARIO: <?php echo htmlspecialchars(strtolower($val['correo'])) ?> - PASSWORD
            <?php echo strtolower($val['password']) ?> -
            Rol:
            <?php echo (strtolower($val['rol'])) ?> - SECTOR: <?php echo strtolower($val['sector']) ?> - ESTADO:
            <?php echo strtolower($val['estado']) ?>
        </p>
    <?php endforeach; ?>
</div>


<form method="post" style="text-align: center; margin-top: 30px;">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id_desafio) ?>">
    <input type="text" name="rol" id="rol_input">
    <br><br>
    <input type="button" value="Enviar" onclick="enviarPayload()">
    <button type="button" onclick="refresh()">Refresh</button>
</form>