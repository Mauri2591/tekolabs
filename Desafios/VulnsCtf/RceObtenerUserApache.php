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
</span>
<br><br>
<?php endif; ?>

<form style="text-align: center; margin-bottom: 20px;" onsubmit="return false;">
    <input style="width: 70%; text-align: center; background-color: #ccc;" name="url_rce_basico" id="url_rce_basico"
        readonly disabled type="text" placeholder="http://anonymous.com/user/?cmd=#"> <i class="text-light"
        title='<?php echo $datos->ayuda ?>'>[?]</i>
    <br> <br>
    <input style="width: 62%; text-align: center;" name="rce_basico" id="rce_basico" type="text">
    <button type="button" onclick="consultarRceBasico()">Consultar</button>
</form>

<div id="resultado_rce_basico" style="margin: .1rem 0;"></div>

<form method="post" style="text-align: center; margin-top: 30px;">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id_desafio) ?>">
    <input type="text" placeholder="***-****" name="respuesta" id="inputSolucion"
        style="text-align: center; width: 50%;" placeholder="respuesta">
    <br>
    <input type="button" value="Enviar" onclick="validarRespuestaRceBasico()">
    <button type="button" onclick="refresh()">Refresh</button>
</form>