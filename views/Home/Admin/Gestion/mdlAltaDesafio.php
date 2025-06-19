<!-- Modal -->
<div class="modal fade" id="modalAltaChallenge" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" id="form_nuevo_evento">
                    <label class="form-control form-control-sm mx-1" for="evento"><span class="text-danger fs-12">*
                        </span> NOMBRE DE LA COMPAÑIA:
                        <input type="text" class="mx-2" placeholder="Ej: EKOPARTY" name="evento" id="evento">
                    </label>
                    <label class="form-control form-control-sm mx-1 my-2" for="fecha_desafio"><span
                            class="text-danger fs-12">*
                        </span> FECHA DEL EVENTO:
                        <input type="date" class="mx-2" name="fecha_evento" id="fecha_evento">
                    </label>
                    <label class="form-control form-control-sm mt-2 mx-1" for="imagen_evento">
                        <span class="text-danger fs-12">*</span> IMAGEN EVENTO:
                        <input type="file" class="mx-2" name="imagen_evento" id="imagen_evento" accept="image/*">
                    </label>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" id="insertEvento" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalEditarChallenge" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <!-- 👈 MODAL EXTRA GRANDE -->
        <div class="modal-content p-3">

            <div class="modal-header">
                <h5 class="modal-title">Actualizar info del Challenge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <table id="table_editar_challenge" class="table table-bordered table-striped text-center w-100">
                    <thead class="table-light">
                        <tr>
                            <th style="text-align: center;">NOMBRE</th>
                            <th style="text-align: center;">PRINCIPAL</th>
                            <th style="text-align: center;">SECUNDARIO</th>
                            <th style="text-align: center;">NIVEL</th>
                            <th style="text-align: center;">GENERO</th>
                            <th style="text-align: center;">TIPO</th>
                            <th style="text-align: center;">VULN</th>
                            <th style="text-align: center;">IMAGEN</th>
                            <th style="text-align: center;">ACCION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTable completa esto automáticamente -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="mdlActualizarChallengeChallenge" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title me-3">Seleccione Trivia o CTF</h5>
                <select
                    class="form-select form-select-sm fw-bold bg-dark border border-dark text-center text-light w-auto"
                    name="select_modalidad" id="select_modalidad" style="min-width: 120px;">
                </select>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <input type="hidden" id="id_desafio_vuln_hidden">
            <input type="hidden" id="id_vulnerabilidad_hidden">
            <input type="hidden" id="id_nivel_vuln_hidden">

            <div class="modal-body">
                <div class="mb-2">
                    <select class="form-select text-light text-center bg-dark border border-dark"
                        name="select_trivia_o_ctf" id="select_trivia_o_ctf">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" onclick="insert_vulnerabilidad_a_challenge()"
                    class="btn btn-sm btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal QUEDE ACA -->
<div class="modal fade" id="modalActualizarChallengeSubirImagen" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-ls">
        <div class="modal-content p-3 border-dark bg-light mt-2">

            <div class="modal-header">
                <h5 class="modal-title">Subir Imagen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="form_editar_challenge_subir_imagen" enctype="multipart/form-data">

                    <input type="hidden" hidden id="id_desafio_hidden" name="id_desafio">

                    <label class="form-control-sm d-flex my-1" title="campo obligatorio" for="nombre">
                        <span class="text-danger fs-12">* </span> SUBIR IMAGEN DEL CHALLENGE:
                    </label> <input type="file" name="imagen" id="imagen" class="form-control form-control-sm mx-1">
                    <br>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="insert_imagen_y_vulnerabilidad_a_challenge()"
                            class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Modal QUEDE ACA -->
<div class="modal fade" id="modal_update_leyenda_challenge" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- 👈 MODAL EXTRA GRANDE -->
        <div class="modal-content p-3 border-dark bg-light mt-2">

            <div class="modal-header">
                <h5 class="modal-title">Leyenda Challenge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <section id="get_datos_desafio_x_id" style="text-align: justify;">

                </section>

                <br>

                <form method="post" id="form_update_leyenda_challenge" enctype="multipart/form-data">

                    <input type="hidden" hidden id="id_desafio_hidden" name="id_desafio">

                    <label class="form-control-sm d-flex my-1" title="campo obligatorio" for="leyenda">
                        <span class="text-danger fs-12">* </span> ESCRIBA AQUI LA LEYENDA DEL CHALLENGE:
                    </label>
                    <textarea name="leyenda" id="leyenda" class="form-control form-control-sm mx-1" rows="10" autofocus
                        placeholder="Ingrese aqui la leyenda" cols="10"></textarea>
                    <br>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="update_leyenda_challenge()"
                            class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Modal QUEDE ACA -->
<div class="modal fade" id="modal_update_genero_challenge" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-ls">
        <!-- 👈 MODAL EXTRA GRANDE -->
        <div class="modal-content p-3 border-dark bg-light mt-2">

            <div class="modal-header">
                <h5 class="modal-title">Genero Challenge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="id_desafio_hidden">
                <input type="hidden" id="id_evento_hidden">
                <input type="hidden" id="id_genero_actual_hidden">
                <input type="hidden" id="id_pagina_hidden">


                <label class="mx-2 d-flex" for="genero"><span class="text-danger text-12">*</span> SELECCIONE:
                </label>
                <select class="form-control form-control-sm" id="select_genero_challenge">
                </select>
                <label class="mx-1 my-2" for="nombre"><span class="text-danger fs-12">*
                    </span> NOMBRE:
                    <input type="text" class="form-control form-control-sm mx-2" name="nombre" id="nombre">
                </label>


                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" onclick="update_genero_challenge()"
                        class="btn btn-sm btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalCargarDatosChallenge" tabindex="-1" aria-labelledby="modalAltaChallengeLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Datos del Challenge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body">
                <form method="post" class="" id="form_editar_challenge_subir_imagen" enctype="multipart/form-data">

                    <input type="hidden" id="id_genero_hidden" name="id_genero">
                    <input type="hidden" id="id_evento_hidden" name="id_evento">


                    <label class="form-control-sm d-flex my-1" title="campo obligatorio" for="nombre">
                        <span class="text-danger fs-12">* </span> NOMBRE:
                    </label> <input type="text" name="nombre_challenge" id="nombre_challenge"
                        placeholder="Ejemplo: DBZ-SAGA-CELL" class="form-control form-control-sm mx-1">

                    <label class="form-control-sm d-flex my-1" title="campo obligatorio" for="nombre">
                        <span class="text-danger fs-12">* </span> PERSONAJE PRINCIPAL:
                    </label> <input type="text" name="personaje_principal" id="personaje_principal"
                        placeholder="Ej: GOHAN" class="form-control form-control-sm mx-1">

                    <label class="form-control-sm d-flex my-1" title="campo obligatorio" for="nombre">
                        <span class="text-danger fs-12">* </span> PERSONAJE SECUNDARIO:
                    </label> <input type="text" name="personaje_secundario" id="personaje_secundario"
                        placeholder="Ej: CELL" class="form-control form-control-sm mx-1">
                    <div class="mt-2">

                        <label class="form-control form-control-sm mt-2 mx-1" for="soundtrack">
                            <span class="text-danger fs-12">*</span> SOUNDTRACK:
                            <input type="file" class="mx-2" name="soundtrack" id="soundtrack" accept="audio/mpeg">
                        </label>
                        <br>
                        <small class="text-danger">Los campos a completar deben respetar el
                            formato de los
                            PLACEHOLDERS como se muestra en los ejemplos.</small>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" id="btnGuardarPaginaChallenge"
                            class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>