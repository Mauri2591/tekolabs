<!-- Modal QUEDE ACA -->
<div class="modal fade" id="modalSorteo" tabindex="-1" aria-labelledby="modalSorteoLabel" aria-hidden="true">
    <div class="modal-dialog modal-ls">
        <div class="modal-content p-3 border-dark bg-light mt-2">

            <input type="hidden" hidden id="valor_hidden_sorteo">
            <input type="hidden" hidden id="valor_hidden_sorteo_usuario_ganador">

            <div>
                <span id="primer_premio" class="text-secondary">PRIMER PREMIO <i class="bi bi-trophy text-warning"
                        style="font-size: 18px;"></i> : <br>
                    Se sortea entre los participantes que al menos
                    resolvieron 1 challenge de nivel <span class="text-danger mt-3 fw-bold text-danger"
                        style="font-size: 14px;">Dificil</span>
                </span>
            </div>

            <div style="display: none;" id="premio_general">
                <span class="text-secondary">
                    SEGUNDO PREMIO <i class="bi bi-star-half text-warning" style="font-size: 18px;"></i> :<br>
                    Se sortea entre los participantes que al menos resolvieron 1 challenge
                </span>
            </div>

            <select id="select_sorteo" onchange="select_sorteo(this.value)" class="form-select fw-bold mt-4"
                aria-label="Default select example">
                <option class="text-center fw-bold" value="1">Primer Puesto</option>
                <option class="text-center fw-bold" value="2">Segundo Puesto</option>
            </select>

            <button id="btnSorteo" onclick="btnSorteo()" class="btn btn-sm btn-primary mt-2">Iniciar</button>

            <div style="display: none;" id="contenedor_mostrar_ganador">
                <div class="d-flex justify-content-center mt-5">
                    <div class="spinner-border p-4" style="font-size: 30px; color:gray" role="status">
                        <span class="sr-only fw-bold"></span>
                    </div>
                </div>
            </div>

            <p id="text-ganador" style="display: none; font-size: 18px; text-align: center;"
                class="text-secondary fw-bold mt-4">El ganador es...
            </p>

            <span id="nombre_jugador_ganador" class="text-success fw-bold mt-4 badge"
                style="display: none; font-size: 26px; font-family: monospace;">

            </span>

        </div>
    </div>
</div>