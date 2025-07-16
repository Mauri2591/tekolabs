let URL = "http://127.0.0.1/tekoLabs/";
document.addEventListener("DOMContentLoaded", function () {
    let grafico = null;

    $.post("../../../../controllers/ctrPaginas.php?case=get_nombre_evento_activo",
        function (data, textStatus, jqXHR) {
            $("#nombre_evento").html(data)
        },
    );

    // Función para actualizar el gráfico
    function actualizarGrafico() {
        $.post("../../../../controllers/ctrPaginas.php?case=get_desafios_grafico_gestion",
            function (data) {
                const total_resueltos = data.map(d => d.total);
                const usuarios = data.map(d => d.nombre); // 👈 cambio clave
                const ctx = document.getElementById('grafico_usuarios_finalizadores_retos');

                if (grafico) {
                    // Si ya existe, actualizamos sus datos
                    grafico.data.labels = usuarios;
                    grafico.data.datasets[0].data = total_resueltos;
                    grafico.update();
                } else {
                    // Primera vez: crear el gráfico
                    grafico = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: usuarios,
                            datasets: [{
                                label: 'RESUELTOS',
                                data: total_resueltos,
                                borderWidth: 2,
                                borderColor: [
                                    'rgb(168, 81, 0)'
                                ], backgroundColor: 'rgb(255, 123, 0)'
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            },
            "json"
        );
    }
    // Carga inicial del gráfico
    actualizarGrafico();

    // Actualización periódica (cada 15 segundos)
    // setInterval(() => {
    //     if ($.fn.DataTable.isDataTable('#table_desafios')) {
    //         $('#table_desafios').DataTable().ajax.reload(null, false);
    //     }

    //     actualizarGrafico();

    // }, 20000);

    if ($.fn.DataTable.isDataTable('#table_desafios')) {
        $('#table_desafios').DataTable().ajax.reload(null, false);
    }

    actualizarGrafico();

    // Inicializar DataTable
    tabla = $("#table_desafios").DataTable({
        "ajax": {
            url: "../../../../controllers/ctrPaginas.php?case=get_desafios_tabla_gestion",
            type: "post",
            dataType: "json",
            error: function (e) {
                console.warn("Error en el ajax de DataTable", e);
            }
        },
        "order": [[1, "desc"]],
        "bDestroy": true,
        "responsive": true,
        "searching": true,
        "info": true,
        "paging": true,
        "pageLength": 10,
        "autoWidth": true,
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún desafío disponible en esta tabla",
            "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ desafíos",
            "sInfoEmpty": "Mostrando 0 a 0 de 0 desafíos",
            "sInfoFiltered": "(filtrado de _MAX_ desafíos totales)",
            "sLoadingRecords": "Cargando...",
            "sSearch": "Buscar:",
            "oAria": {
                "sSortAscending": ": Activar para ordenar de forma ascendente",
                "sSortDescending": ": Activar para ordenar de forma descendente"
            }
        }
    });

    tabla = $("#table_get_eventos").DataTable({
        "ajax": {
            url: "../../../../controllers/ctrPaginas.php?case=get_eventos",
            type: "post",
            dataType: "json",
            error: function (e) {
                console.warn("Error en el ajax de DataTable", e);
            }
        },
        "order": [[0, "asc"]],
        "bDestroy": true,
        "responsive": true,
        "searching": false,
        "info": false,
        "paging": false,
        "iDisplayLength": 20,
        "autoWidth": true,
        "language": {
            "sProcessing": "Procesando..",
            "sLengthMenu": "",
            "sZeroRecords": "No se encontraron resultados..",
            "sEmptyTable": "Ningun challenge disponible en esta tabla",
            "sLoadingRecords": "Cargando",
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de forma ascendente",
                "sSortDescending": ": Activar para ordenar la columna de forma descendente"
            }
        }
    });

    // Limpia el formulario cada vez que se abre el modal
    $('#modalAltaChallenge').on('show.bs.modal', function () {
        document.getElementById("form_nuevo_evento").reset();
    });

    $('#modalActualizarChallengeSubirImagen').on('hidden.bs.modal', function () {
        document.getElementById("form_creacion_challenge").reset();
    });

    $('#modalupdate_leyenda_challenge').on('hidden.bs.modal', function () {
        document.getElementById("form_update_leyenda_challenge").reset();
    });

    $('#modalEditarChallenge').on('hidden.bs.modal', function () {
        window.location.reload()
    });

    // $('#modalCargarDatosChallenge').on('hidden.bs.modal', function () {
    //     document.getElementById("form_editar_challenge_subir_imagen").reset();
    // });

    function get_datos_form_nuevo_evento() {
        let formData = new FormData();

        let imagenInput = document.getElementById("imagen_evento");
        if (imagenInput.files.length > 0) {
            formData.append('imagen', imagenInput.files[0]);
        }

        formData.append('evento', document.getElementById("evento").value);
        formData.append('fecha_evento', document.getElementById("fecha_evento").value);
        return formData;
    }

    document.getElementById("insertEvento").addEventListener("click", function () {
        $.ajax({
            type: "POST",
            url: "../../../../controllers/ctrPaginas.php?case=insert_evento",
            data: get_datos_form_nuevo_evento(),
            processData: false,
            contentType: false,
            success: function (response) {
                $('#table_get_eventos').DataTable().ajax.reload(null, false);
                Swal.fire({
                    icon: "success",
                    title: "Bien",
                    text: "Evento insertado correctamente",
                    showConfirmButton: false,
                    showCancelButton: false,
                    timer: 1500
                })
                $("#modalAltaChallenge").modal("hide");
            }, error: function () {
                Swal.fire({
                    icon: "warning",
                    title: "Error",
                    text: "Campos vacios",
                    showConfirmButton: true,
                    showCancelButton: true
                })
            }
        });
    })


});


function cambiarEstadoEvento(id, id_estado) {
    $.post("../../../../controllers/ctrPaginas.php?case=get_count_total_id_estados_eventos",
        function (data, textStatus, jqXHR) {
            if (data.total < 1) {
                Swal.fire({
                    icon: "warning",
                    title: "Error!",
                    text: "Operacion no permitida, solo de debe gestionar de un solo EVENTO ACTIVO",
                    showConfirmButton: true,
                    showCancelButton: true
                })
                if (id_estado == "0") {
                    Swal.fire({
                        icon: "info",
                        title: "Atencion!",
                        text: "Desea pasar el evento a ACTIVO?",
                        showConfirmButton: true,
                        showCancelButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post("../../../../controllers/ctrPaginas.php?case=update_evento_activo_inactivo", { id: id, id_estado: 1 },
                                function (data, textStatus, jqXHR) {
                                },
                                "json"
                            );
                            // $('#table_get_eventos').DataTable().ajax.reload(null, false);
                            Swal.fire({
                                icon: "success",
                                title: "Bien",
                                text: "Evento ACTIVADO correctamente",
                                showConfirmButton: false,
                                showCancelButton: false,
                                timer: 1500
                            })
                            $("#modalAltaChallenge").modal("hide");
                            document.getElementById("form_nuevo_evento").reset();

                            setTimeout(() => {
                                if ($.fn.DataTable.isDataTable('#table_get_eventos')) {
                                    $('#table_get_eventos').DataTable().ajax.reload(null, false);
                                }
                            }, 500);
                        }
                    })
                }
            } else {
                if (id_estado == "1") {
                    Swal.fire({
                        icon: "info",
                        title: "Atencion!",
                        text: "Desea pasar el evento a INACTIVO?",
                        showConfirmButton: true,
                        showCancelButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post("../../../../controllers/ctrPaginas.php?case=update_evento_activo_inactivo", { id: id, id_estado: 0 }, //antes id_estado=0
                                function (data, textStatus, jqXHR) {
                                },
                                "json"
                            );
                            // $('#table_get_eventos').DataTable().ajax.reload(null, false);
                            Swal.fire({
                                icon: "success",
                                title: "Bien",
                                text: "Evento ACTIVADO correctamente",
                                showConfirmButton: false,
                                showCancelButton: false,
                                timer: 1500
                            })
                            $("#modalAltaChallenge").modal("hide");
                            document.getElementById("form_nuevo_evento").reset();

                            setTimeout(() => {
                                if ($.fn.DataTable.isDataTable('#table_get_eventos')) {
                                    $('#table_get_eventos').DataTable().ajax.reload(null, false);
                                }
                            }, 500);
                        }
                    })
                }
            }

        },
        "json"
    );

}

function nuevoChallenge() {
    $("#modalAltaChallenge").modal("show");
}

function eliminar_evento(id) {
    Swal.fire({
        icon: "info",
        title: "Atencion!",
        text: "Desea eliminar este evento?",
        showConfirmButton: true,
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../../../controllers/ctrPaginas.php?case=delete_evento", { id: id },
                function (data, textStatus, jqXHR) {
                },
                "json"
            );
            // $('#table_get_eventos').DataTable().ajax.reload(null, false);
            Swal.fire({
                icon: "success",
                title: "Bien",
                text: "Evento inseliminadoertado correctamente",
                showConfirmButton: false,
                showCancelButton: false,
                timer: 1500
            })
            $("#modalAltaChallenge").modal("hide");
            document.getElementById("form_nuevo_evento").reset();

            setTimeout(() => {
                if ($.fn.DataTable.isDataTable('#table_get_eventos')) {
                    $('#table_get_eventos').DataTable().ajax.reload(null, false);
                }
            }, 500);
        }
    })
}


function alta_pagina(id, id_genero) {
    // Guardá el id_genero en un campo oculto para que se use luego
    document.getElementById("id_genero_hidden").value = id_genero;
    document.getElementById("id_evento_hidden").value = id;
    $("#modalCargarDatosChallenge").modal("show");
}

function editar_challenge(id, id_genero) {
    tabla = $("#table_editar_challenge").DataTable({
        "ajax": {
            url: "../../../../controllers/ctrPaginas.php?case=get_desafios_y_paginas",
            type: "post",
            data: { id: id },
            dataType: "json",
            error: function (e) {
                console.warn("Error en el ajax de DataTable", e);
            }
        },
        "order": [[0, "asc"]],
        "bDestroy": true,
        "responsive": true,
        "searching": false,
        "info": false,
        "paging": false,
        "iDisplayLength": 20,
        "autoWidth": true,
        "language": {
            "sProcessing": "Procesando..",
            "sLengthMenu": "",
            "sZeroRecords": "No se encontraron resultados..",
            "sEmptyTable": "Ningun challnege disponible en esta tabla",
            "sLoadingRecords": "Cargando",
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de forma ascendente",
                "sSortDescending": ": Activar para ordenar la columna de forma descendente"
            }
        }
    });
    // Guardá el id_genero en un campo oculto para que se use luego
    document.getElementById("id_genero_hidden").value = id_genero;
    document.getElementById("id_evento_hidden").value = id;
    $("#modalEditarChallenge").modal("show");
}



document.getElementById("btnGuardarPaginaChallenge").addEventListener("click", function () {
    const nombre = document.getElementById("nombre_challenge").value.trim();
    const personaje_principal = document.getElementById("personaje_principal").value.trim();
    const personaje_secundario = document.getElementById("personaje_secundario").value.trim();
    const id_genero = document.getElementById("id_genero_hidden").value;
    const id_evento = document.getElementById("id_evento_hidden").value;

    // Validación rápida
    if (!nombre || !personaje_principal || !personaje_secundario || !id_genero) {
        Swal.fire({
            icon: "warning",
            title: "Campos incompletos",
            text: "Por favor completá todos los campos antes de guardar",
            confirmButtonText: "Ok"
        });
        return;
    }

    // Construcción de FormData
    let formData = new FormData();
    formData.append("nombre", nombre);
    formData.append("personaje_principal", personaje_principal);
    formData.append("personaje_secundario", personaje_secundario);
    formData.append("id_evento", id_evento);
    formData.append("id_genero", id_genero);
    formData.append("id_estado", 1); // valor fijo

    // Agrega el archivo MP3 si existe
    let soundtrackInput = document.getElementById("soundtrack");
    if (soundtrackInput.files.length > 0) {
        formData.append("soundtrack", soundtrackInput.files[0]);
    }

    $.ajax({
        type: "POST",
        url: "../../../../controllers/ctrPaginas.php?case=insert_pagina_challenge",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            Swal.fire({
                icon: "success",
                title: "Bien",
                text: "Challenge creado correctamente",
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(() => {
                if ($.fn.DataTable.isDataTable('#table_get_eventos')) {
                    // window.location.reload();
                    $('#table_get_eventos').DataTable().ajax.reload(null, false);
                }
            }, 500);
            $("#modalCargarDatosChallenge").modal("hide");
            document.getElementById("form_creacion_challenge").reset();


            document.getElementById("form_creacion_challenge").reset();
        },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Hubo un problema al guardar el challenge",
                confirmButtonText: "Aceptar"
            });
            console.error("Error al guardar:", error);
        }
    });
});

function actualizarChallenge(id_desafio) {
    $("#id_desafio_hidden").val(id_desafio)
    $("#modalActualizarChallengeSubirImagen").modal("show")
}

function actualizarChallengeLeyenda(id) {

    $("#id_desafio_hidden").val(id)
    $("#modal_update_leyenda_challenge").modal("show");

    $.post("../../../../controllers/ctrPaginas.php?case=get_datos_desafio_x_id", { id: id },
        function (data, textStatus, jqXHR) {
            $("#get_datos_desafio_x_id").html(data)
        },
        "html"
    );

    $.post("../../../../controllers/ctrPaginas.php?case=get_leyenda_desafio", { id: id },
        function (data, textStatus, jqXHR) {
            $("#leyenda").val(data.leyenda)
        },
        "json"
    );
}

function update_leyenda_challenge() {
    $.ajax({
        type: "POST",
        url: "../../../../controllers/ctrPaginas.php?case=update_leyenda_challenge",
        data: { id: $("#id_desafio_hidden").val(), leyenda: $("#leyenda").val() },
        // processData: false,
        // contentType: false,
        success: function (response) {
            Swal.fire({
                icon: "success",
                title: "Leyenda actualizada",
                text: "La leyenda fue asociada correctamente al desafío.",
                timer: 1500,
                showConfirmButton: false
            });
            setTimeout(() => {
                if ($.fn.DataTable.isDataTable('#table_editar_challenge')) {
                    $('#table_editar_challenge').DataTable().ajax.reload(null, false);
                }
                actualizarGrafico();

            }, 500);
            $("#modal_update_leyenda_challenge").modal("hide");
            document.getElementById("form_update_leyenda_challenge").reset();
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: xhr.responseText || "No se pudo guardar la leyenda.",
            });
        }
    });
}

function actualizarChallengeGenero(id) {
    $("#modal_update_genero_challenge").modal("show")
}

function actualizarChallengeGenero(id_desafio, id_evento, id_genero_actual, id_pagina) {
    $("#id_desafio_hidden").val(id_desafio);
    $("#id_evento_hidden").val(id_evento);
    $("#id_genero_actual_hidden").val(id_genero_actual);
    $("#id_pagina_hidden").val(id_pagina);

    $.post("../../../../controllers/ctrPaginas.php?case=get_nombre_pagina", { id: id_pagina },
        function (data, textStatus, jqXHR) {
            $("#nombre").val(data.nombre);
        },
        "json"
    );

    $.ajax({
        url: "../../../../controllers/ctrPaginas.php?case=get_generos_por_evento_y_desafio",
        type: "POST",
        data: {
            id_evento: id_evento,
            id_desafio: id_desafio
        },
        success: function (response) {
            const generos = JSON.parse(response);
            let opciones = "";
            generos.forEach(g => {
                opciones += `<option value="${g.id}">${g.genero}</option>`;
            });
            $("#select_genero_challenge").html(opciones);
            $("#select_genero_challenge").val(id_genero_actual); // 👈 Preselecciona correctamente
            $("#modal_update_genero_challenge").modal("show");

            setTimeout(() => {
                $('#modalEditarChallenge').on('hidden.bs.modal', function () {
                    window.location.reload()
                });
            }, 5000);
        },
        error: function (xhr) {
            console.error("Error cargando géneros:", xhr);
        }
    });
}

function update_genero_challenge() {
    const id_desafio = $("#id_desafio_hidden").val();
    const id_genero = $("#select_genero_challenge").val();
    const nombre = $("#nombre").val();
    if (!id_genero) {
        Swal.fire({
            icon: "warning",
            title: "Campo obligatorio",
            text: "Debe seleccionar un género.",
        });
        return;
    }

    $.ajax({
        type: "POST",
        url: "../../../../controllers/ctrPaginas.php?case=update_genero_challenge",
        data: {
            id_desafio: id_desafio,
            id_genero: id_genero,
            nombre: nombre
        },
        success: function (response) {
            Swal.fire({
                icon: "success",
                title: "Género actualizado",
                text: "El género fue actualizado correctamente.",
                timer: 1500,
                showConfirmButton: false
            });

            setTimeout(() => {
                $('#modal_update_genero_challenge').modal("hide");

                if ($.fn.DataTable.isDataTable('#table_editar_challenge')) {
                    $('#table_editar_challenge').DataTable().ajax.reload(null, false);
                }

                actualizarGrafico();
            }, 500);
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: xhr.responseText || "No se pudo actualizar el género.",
            });
        }
    });
}


function insert_imagen_y_vulnerabilidad_a_challenge() {
    let form = document.getElementById("form_editar_challenge_subir_imagen");
    let formData = new FormData(form);

    $.ajax({
        type: "POST",
        url: "../../../../controllers/ctrPaginas.php?case=insert_imagen_challenge",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            Swal.fire({
                icon: "success",
                title: "Imagen actualizada",
                text: "La imagen fue asociada correctamente al desafío.",
                timer: 1500,
                showConfirmButton: false
            });
            setTimeout(() => {
                if ($.fn.DataTable.isDataTable('#table_editar_challenge')) {
                    $('#table_editar_challenge').DataTable().ajax.reload(null, false);
                }
                actualizarGrafico();

            }, 500);
            $("#modalActualizarChallengeSubirImagen").modal("hide");
            document.getElementById("form_editar_challenge_subir_imagen").reset();
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: xhr.responseText || "No se pudo subir la imagen.",
            });
        }
    });
}

document.getElementById("select_modalidad").addEventListener("change", function () {
    $("#id_vulnerabilidad_hidden").val(this.value)
    $.post("../../../../controllers/ctrPaginas.php?case=get_vulnerabilidad", { id_modalidad: this.value, id_nivel: $("#id_nivel_vuln_hidden").val() },
        function (data, textStatus, jqXHR) {
            $("#select_trivia_o_ctf").html(data);
        },
        "html"
    );
});

document.querySelectorAll(".btn_ir_challenge").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
        e.preventDefault();
        const idChallenge = this.dataset.id;

        Swal.fire({
            title: 'Acceso al desafío',
            html: `
                    <input type="email" id="email" class="swal2-input" placeholder="Ingrese su email">
                `,
            confirmButtonText: 'Ingresar',
            focusConfirm: false,
            preConfirm: () => {
                const email = Swal.getPopup().querySelector('#email').value;
                if (!email || !/^\S+@\S+\.\S+$/.test(email)) {
                    Swal.showValidationMessage(`Por favor ingrese un email válido`);
                }
                return { email: email };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // (Opcional) Enviar el email por AJAX al backend
                /*
                $.post('ruta_guardar_email.php', { email: result.value.email });
                */

                // Redirigir al desafío
                window.open(`../Desafios/?ch=${idChallenge}`, '_blank');
            }
        });
    });
});
function insert_vulnerabilidad_a_challenge() {
    $.ajax({
        type: "POST",
        url: "../../../../controllers/ctrPaginas.php?case=update_insertar_vulnerabilidad_challenge",
        data: { id: $("#id_desafio_vuln_hidden").val(), id_vulnerabilidad_o_tematica: $("#select_trivia_o_ctf").val() },
        dataType: "json",
        success: function (response) {
            Swal.fire({
                icon: "success",
                title: "Challenge actualizad",
                text: "La vulnerabilidad o trivia fue asociada correctamente al desafío.",
                timer: 1500,
                showConfirmButton: false
            });
            setTimeout(() => {
                if ($.fn.DataTable.isDataTable('#table_editar_challenge')) {
                    $('#table_editar_challenge').DataTable().ajax.reload(null, false);
                }
                actualizarGrafico();

            }, 500);
            $("#modalActualizarChallengeSubirImagen").modal("hide");
            document.getElementById("form_editar_challenge_subir_imagen").reset();
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: xhr.responseText || "No se pudo subir la vuln.",
            });
        }
    });
}

// Ejecutar el change automáticamente al cargar el modal
$('#mdlActualizarChallengeChallenge').on('shown.bs.modal', function () {
    document.getElementById("select_modalidad").dispatchEvent(new Event("change"));
});


function actualizarChallengeChallenge(id_desafio, id_nivel) {
    $("#id_desafio_vuln_hidden").val(id_desafio);
    $("#id_nivel_vuln_hidden").val(id_nivel);

    $("#mdlActualizarChallengeChallenge").modal("show");

    $.post("../../../../controllers/ctrPaginas.php?case=get_modalidades",
        function (data, textStatus, jqXHR) {
            $("#select_modalidad").html(data)
        },
        "html"
    );
}

function sorteo() {
    $("#modalSorteo").modal("show");
    $("#valor_hidden_sorteo").val(1)
    document.getElementById("btnSorteo").style.display = "block";
    document.getElementById("nombre_jugador_ganador").innerText = "";
    document.getElementById("nombre_jugador_ganador").style.display = "none";
    document.getElementById("select_sorteo").value = 1;
    document.getElementById("primer_premio").style.display = "block";
    document.getElementById("premio_general").style.display = "none";
}

function select_sorteo(valor) {
    if (valor == "2") {
        document.getElementById("primer_premio").style.display = "none";
        document.getElementById("premio_general").style.display = "flex";
        document.getElementById("btnSorteo").style.display = "block";
        $("#valor_hidden_sorteo").val(valor)
        document.getElementById("contenedor_mostrar_ganador").style.display = "none";
        document.getElementById("nombre_jugador_ganador").style.display = "none";

    } else if (valor == "1") {
        document.getElementById("primer_premio").style.display = "block";
        document.getElementById("premio_general").style.display = "none";
        $("#valor_hidden_sorteo").val(valor)
        document.getElementById("contenedor_mostrar_ganador").style.display = "none";
        document.getElementById("btnSorteo").style.display = "block";
        document.getElementById("nombre_jugador_ganador").style.display = "none";
    }
}

function btnSorteo() {
    let VALOR = $("#valor_hidden_sorteo").val();
    if (VALOR == "1") {
        $.post("../../../../controllers/ctrPaginas.php?case=get_cantidad_participantes_sorteo_principal",
            function (data, textStatus, jqXHR) {

                $.post("../../../../controllers/ctrPaginas.php?case=get_nombre_usuario_ganador_sorteo", { id: data },
                    function (data, textStatus, jqXHR) {
                        let dato = JSON.parse(data)
                        $("#valor_hidden_sorteo_usuario_ganador").val(dato.usuario)
                    },
                );
            },
            "json"
        );
    } else if (VALOR == "2") {
        $.post("../../../../controllers/ctrPaginas.php?case=get_cantidad_participantes_sorteo_secundario",
            function (data, textStatus, jqXHR) {

                $.post("../../../../controllers/ctrPaginas.php?case=get_nombre_usuario_ganador_sorteo", { id: data },
                    function (data, textStatus, jqXHR) {
                        let dato = JSON.parse(data)
                        $("#valor_hidden_sorteo_usuario_ganador").val(dato.usuario)
                    },
                );
            },
            "json"
        );
    }

    document.getElementById("contenedor_mostrar_ganador").style.display = "block";
    document.getElementById("btnSorteo").style.display = "none";

    setTimeout(() => {
        document.getElementById("contenedor_mostrar_ganador").style.display = "none";
        document.getElementById("text-ganador").style.display = "block";

        setTimeout(() => {
            document.getElementById("text-ganador").style.display = "none";
            document.getElementById("nombre_jugador_ganador").style.display = "block";

            let GANADOR = $("#valor_hidden_sorteo_usuario_ganador").val()
            $("#nombre_jugador_ganador").text(GANADOR)
        }, 2000);
    }, 2000);

}