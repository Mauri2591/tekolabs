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
    </span><i class="text-light" title='curl -H "Content-type: application/json" -d &#39;{"user":"all"}&#39; URL'>[?]</i>
    <br><br>
<?php endif; ?>

<form style="text-align: center; margin-bottom: 20px;" onsubmit="return false;">
    <input style="width: 70%; text-align: center; background-color: #ccc;" name="url_curl" id="url_curl" readonly
        disabled type="text" placeholder="http://anonymous.com/user/">
    <br> <br>
    <input style="width: 62%;" name="curl" id="curl" type="text" placeholder="curl ....">
    <button type="button" onclick="consultarCurlEnvioPeticionHeaderYBody()">Consultar</button>
</form>

<div id="resultadoCurlEnvioPeticionHeaderYBody" style="margin: .1rem 0;"></div>

<form method="post" style="text-align: center; margin-top: 30px;">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id_desafio) ?>">
    <input type="text" name="respuesta" id="inputSolucion" style="text-align: center; width: 50%;"
        placeholder="respuesta">
    <br>
    <input type="button" value="Enviar" onclick="validarRespuestaConsultarCurlEnvioPeticionHeaderYBody()">
    <button type="button" onclick="refresh()">Refresh</button>
</form>