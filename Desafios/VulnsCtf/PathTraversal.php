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
    <input style="width: 70%; text-align: center; background-color: #ccc;" name="url_path_traversal"
        id="url_path_traversal" readonly disabled type="text" placeholder="http://anonymous.com/perfiles/?file=">
    <i class="text-light" title="<?php echo $datos->ayuda ?>">[?]</i>
    <br> <br>
    <input style="width: 62%;" name="pathTraversal" id="pathTraversal" type="text"
        placeholder="ingrese aqui su payload">
    <button type="button" onclick="consultarPathTraversal()">Consultar</button>
</form>

<div id="resultadoPathTraversal" style="margin: .1rem 0;"></div>

<form method="post" style="text-align: center; margin-top: 30px;">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id_desafio) ?>">
    <input type="text" name="respuesta" id="inputSolucion" style="text-align: center; width: 50%;"
        placeholder="respuesta">
    <br>
    <input type="button" value="Enviar" onclick="validarRespuestaPathTraversal()">
    <button type="button" onclick="refresh()">Refresh</button>
</form>