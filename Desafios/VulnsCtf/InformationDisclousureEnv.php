<?php
$vuln = new Config();
$id_desafio = $_POST['id'] ?? ($_GET['id'] ?? null);
$datos = $id_desafio ? $vuln->get_vulnerabilidad_challenge_x_id($id_desafio) : null;
?>

<!-- DESCRIPCIÓN DEL DESAFÍO -->
<?php if ($datos): ?>
    <span style="color: #33ff33; font-family: monospace;">
        <?= nl2br(htmlspecialchars($datos->descripcion)) ?>
    </span>
    <br><br>
<?php endif; ?>

<form style="text-align: center; margin-bottom: 20px;" onsubmit="return false;">
    <input style="width: 70%;" name="env" id="input_env" type="text" placeholder="/.archivo">
    <button type="button" onclick="consultarArchivo()">Consultar</button><i class="text-light"
        title="<?php echo $datos->ayuda ?>"> [?]</i>
</form>

<div id="resultado_env" style="margin-top: 1rem;"></div>

<form method="post" style="text-align: center; margin-top: 30px;">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id_desafio) ?>">
    <input type="text" name="respuesta" id="inputSolucion" placeholder="clave codificada">
    <br><br>
    <input type="button" value="Enviar" onclick="validarRespuesta()">
    <button type="button" onclick="refresh()">Refresh</button>
</form>