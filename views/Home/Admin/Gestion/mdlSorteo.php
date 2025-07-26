<!-- Modal QUEDE ACA -->
<div class="modal fade border border-warning" style="background-color: #40005eff;" id="modalSorteo" tabindex="-1"
    aria-labelledby="modalSorteoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl mt-5 border border-light">
        <div class="modal-content bg-dark px-4 pt-2 pb-5">

            <input type="hidden" hidden id="valor_hidden_sorteo">
            <input type="hidden" hidden id="valor_hidden_sorteo_usuario_ganador">

            <div>
                <span id="primer_premio" style="font-size: 2.5rem;" class="text-light"><span class="fw-bold">Primer
                        Premio</span> <i class="bi bi-trophy-fill" style="font-size: 3rem; color:orange"></i><br>
                    <span style="font-size: 1.5rem;">Se sortea entre los participantes que al menos
                        resolvieron 1 challenge de nivel </span> <span class="text-danger mt-3 fw-bold text-danger"
                        style="font-size: 1.7rem;">DIFICIL</span>
                </span>
            </div>

            <div style="display: none;" id="premio_general">
                <span class="text-light" style="font-size: 2.5rem;">
                    <span class="fw-bold">Segundo
                        Premio</span> <i class="bi bi-trophy-fill" style="font-size: 3rem; color:gray"></i><br>
                    <span style="font-size: 1.5rem;"> Se sortea entre los participantes que al menos resolvieron 1
                        challenge</span>
                </span>
            </div>

            <select id="select_sorteo" onchange="select_sorteo(this.value)" class="form-select fw-bold mt-4 mb-1"
                aria-label="Default select example">
                <option class="text-center fw-bold" value="1">Primer Puesto</option>
                <option class="text-center fw-bold" value="2">Segundo Puesto</option>
            </select>

            <button id="btnSorteo" style="width: 75%; margin: 0 auto; margin-bottom: 3rem; margin-top: 1.2rem;"
                onclick="btnSorteo()" class="btn btn-sm fw-bold btn-outline-warning">Iniciar Sorteo</button>

            <div style="display: none;" id="contenedor_mostrar_ganador">
                <div class="d-flex justify-content-center mt-5">
                    <div class="spinner-border"
                        style="font-size: 7rem; font-weight: bold; color:limegreen; padding: 4rem;" role="status">
                        <span class="sr-only fw-bold"></span>
                    </div>
                </div>
            </div>

            <p id="text-ganador"
                style="display: none; font-size: 2.3rem; font-style: italic; text-align: center; color:limegreen"
                class="mt-4">El ganador es...
            </p>

            <span id="nombre_jugador_ganador" class="mt-4 badge bg-dark"
                style="display: none; font-size: 4rem; color:limegreen; font-family: monospace;">
            </span>

        </div>
    </div>
</div>