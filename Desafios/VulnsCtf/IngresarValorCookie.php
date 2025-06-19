<?php
$vuln = new Config();
$id_desafio = $_POST['id'] ?? ($_GET['id'] ?? null);
$datos = $id_desafio ? $vuln->get_vulnerabilidad_challenge_x_id($id_desafio) : null;
?>

<script>
    let SOLUCION_GLOBAL = "<?php echo $datos->solucion ?>"
</script>

<!-- DESCRIPCIÓN DEL DESAFÍO -->
<?php if ($datos): ?>
    <span style="color: #33ff33; font-family: monospace;">
        <?= nl2br(htmlspecialchars($datos->descripcion)) ?>
    </span><i class="text-light" title="<?php echo $datos->ayuda ?>">[?]</i>
    <br><br>
<?php endif; ?>


<form style="text-align: center; margin-bottom: 20px;" onsubmit="return false;">
    <br>
    <input style="width: 62%; text-align: center;" placeholder="********.******" name="obtener_cookie"
        id="obtener_cookie" type="text">
    <button type="button" onclick="consultarValorCookie()">Consultar</button>
</form>

<div id="resultado_obtener_cookie" style="margin: .1rem 0;"></div>

<form method="post" style="text-align: center; margin-top: 30px;">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id_desafio) ?>">
    <input type="text" name="respuesta" id="inputSolucion" style="text-align: center; width: 50%;"
        placeholder="Ingrese el valor de la cookie tal cual figura">
    <br>
    <input type="button" value="Enviar" onclick="validarRespuestaIngresarValorCookie()">
    <button type="button" onclick="refresh()">Refresh</button>
</form>