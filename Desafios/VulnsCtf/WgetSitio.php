<?php
require_once __DIR__ . "/../../config/Conexion.php";
require_once __DIR__ . "/../../config/Config.php";


class WgetSitio extends Conexion
{
    public function get_vulnerabilidad_challenge_x_id($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT vulnerabilidades_o_tematicas.id,
                       vulnerabilidades_o_tematicas.nombre,
                       vulnerabilidades_o_tematicas.descripcion,
                       vulnerabilidades_o_tematicas.archivo_php,
                       vulnerabilidades_o_tematicas.solucion,
                       vulnerabilidades_o_tematicas.ayuda,
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

$id_desafio = $_POST['id'] ?? ($_GET['id'] ?? null);
$info = new WgetSitio();
$datos = $id_desafio ? $info->get_vulnerabilidad_challenge_x_id($id_desafio) : null;
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
    <input style="width: 62%;" name="wget" id="wget" type="text">
    <button type="button" onclick="consultarWgetSitio()">Consultar</button>
</form>

<div id="resultadoWgetSitio" style="margin: .1rem 0;"></div>

<form method="post" style="text-align: center; margin-top: 30px;">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id_desafio) ?>">
    <input type="text" name="respuesta" id="inputSolucion" style="text-align: center; width: 50%;"
        placeholder="**********">
    <br>
    <input type="button" value="Enviar" onclick="validarRespuestaWgetSitio()">
    <button type="button" onclick="refresh()">Refresh</button>
</form>