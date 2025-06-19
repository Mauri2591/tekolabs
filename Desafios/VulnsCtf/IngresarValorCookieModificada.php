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
    <button type="button" onclick="mostrarDecoder()" class="btn btn-sm btn-outline-success py-0">Decoder</button>
    <br><br>
<?php endif; ?>


<form style="text-align: center; margin-bottom: 20px;" onsubmit="return false;">
    <br>
    <input style="width: 62%; text-align: center;" placeholder="********.******" name="obtener_cookie_modificada"
        id="obtener_cookie_modificada" type="text">
    <button type="button" onclick="consultarValorCookieModificada()">Consultar</button>
</form>

<div id="resultado_obtener_cookie_modificada" style="margin: .1rem 0;"></div>

<form method="post" style="text-align: center; margin-top: 30px;">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id_desafio) ?>">
    <input type="text" name="respuesta" id="inputSolucion" style="text-align: center; width: 50%;"
        placeholder="Ingrese el valor de la cookie tal cual figura">
    <br>
    <input type="button" value="Enviar" onclick="validarRespuestaValorCookieModificada()">
    <button type="button" onclick="refresh()">Refresh</button>
</form>
<br><br>

<div id="contenedor_decorer" class="col-lg-5 p-1 bg-dark" style="border: .1rem solid lime; display: none;">
    <select onchange="idOptionCodificar(this.value)" name="idOptionCodificar" id="idOptionCodificar">
        <option value="1">Codificar B64</option>
        <option value="2">Decodificar B64</option>
    </select>
    <br>
    <input type="hidden" id="input_cod_enc_hidden" value="1">
    <input oninput="input_cod_enc()" id="input_cod_enc" type="text" placeholder="Ingrese el valor"
        style="width: 100%; text-align: center;">
    <br>
    <textarea name="textarea_cod_enc" style="width: 100%; height: 4rem;" id="textarea_cod_enc"></textarea>
</div>