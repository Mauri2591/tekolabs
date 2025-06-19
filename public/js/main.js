document.addEventListener("DOMContentLoaded", function () {
    // Primero cargar los audios
    $.post("controllers/ctrPaginas.php?case=get_audio_pagina",
        function (data, textStatus, jqXHR) {
            document.getElementById("cont_audio_pagina").innerHTML = data;

            document.getElementById("combo_genero").addEventListener("change", function () {
                const idPagina = this.value;
                const idGenero = this.options[this.selectedIndex].dataset.genero;

                // NUEVO BLOQUE: si selecciona el valor 0, ocultar el título y salir
                if (idPagina === "0") {
                    document.getElementById("cont_challenge_titulo").style.display = "none";

                    // Opcional: mostrar el texto de acceso granted
                    let html = `
                        <h1 style="color:lime; background:black; font-family: monospace; text-align:center; padding: 1rem;">
                            <marquee scrollamount="8">
                                &lt;/&gt; [ access granted ] :: root@telecom_ctf ~# ./iniciar_reto.sh
                            </marquee>
                        </h1>`;
                    $("#section_cont_sitios").html(html);
                    // Detener todos los audios al volver a "SELECCIONE"
                    document.querySelectorAll("audio").forEach(audio => {
                        audio.pause();
                        audio.currentTime = 0;
                    });

                    return; // IMPORTANTE: detener la ejecución aquí
                }

                // Mostrar el título si se selecciona una opción válida
                document.getElementById("cont_challenge_titulo").style.display = "block";

                // Reproducir audio
                document.querySelectorAll("audio").forEach(audio => {
                    audio.pause();
                    audio.currentTime = 0;
                });

                const audio = document.querySelector(`audio[data-genero="${idGenero}"]`);
                if (audio) {
                    audio.play().catch(err => console.warn("Error al reproducir:", err));
                }
                // Cargar desafíos + título
                $.post("controllers/ctrPaginas.php?case=get_chellenges_pagina", { id: idPagina }, function (data) {
                    const json = JSON.parse(data);
                    $("#titulo_challenge").text(json.titulo);
                    $("#section_cont_sitios").html(json.html);
                });
            });
        },
        "html"
    );

    // Después cargar el combo
    $.post("controllers/ctrPaginas.php?case=get_datos_combo_generos_evento_pagina_inicio",
        function (data, textStatus, jqXHR) {
            document.getElementById("combo_genero").innerHTML = data;
        },
        "html"
    );

    $.post("controllers/ctrPaginas.php?case=get_datos_icono_evento_pagina_inicio",
        function (response) {
            const data = JSON.parse(response);
            $("#imagen_evento").html(data.html);

            if (data.estado === "activo") {
                // Evento habilitado: mostrás todo el contenido interactivo
                let html = `<h1 style="color:lime; background:black; font-family: monospace; text-align:center; padding: 1rem;">
                    <marquee scrollamount="8">
                        &lt;/&gt; [access granted] :: root@telecom_ctf ~# ./iniciar_reto.sh
                    </marquee>
                </h1>`;
                $("#section_cont_sitios").html(html);

                // Mostrar combo y cargar audios normalmente
                $("#combo_genero").prop("disabled", false);
                $("#parrafo_inicio_combo").text("Elige un genero y comienza");

                // Cargar audios (reubicado si querés controlar)
                $.post("controllers/ctrPaginas.php?case=get_audio_pagina",
                    function (dataAudio) {
                        document.getElementById("cont_audio_pagina").innerHTML = dataAudio;
                    },
                    "html"
                );
            } else {
                // Evento bloqueado: acceso limitado
                let html = `
                <h1 style="color:red; background:black; font-family: monospace; text-align:center; padding: 1rem;">
                    <marquee scrollamount="8">
                        &lt;/&gt; [access brocked] :: root@telecom_ctf ~# ./permiso_denegado.sh
                    </marquee>
                </h1>
                <p style="color:white; font-family: monospace; text-align: center;">
                    El evento aún no está disponible. Contacta al administrador o intenta mas tarde.
                </p>`;
                $("#section_cont_sitios").html(html);

                // Desactivar combo
                $("#combo_genero").html('<option disabled selected>Bloqueado</option>');
                $("#combo_genero").prop("disabled", true);
                $("#parrafo_inicio_combo").text("Evento bloqueado. Acceso restringido.");

                // Vaciar audios
                $("#cont_audio_pagina").empty();
            }
        },
        "text"
    );

    // Delegación para todos los <a> que estén dentro de #section_cont_sitios
    document.getElementById("section_cont_sitios").addEventListener("click", function (e) {
        const target = e.target.closest("a");
        if (!target) return;

        // Bloquear Ctrl+click / Cmd+click
        if (e.ctrlKey || e.metaKey) {
            e.preventDefault();
        }
    });

    document.getElementById("section_cont_sitios").addEventListener("contextmenu", function (e) {
        const target = e.target.closest("a");
        if (target) {
            e.preventDefault(); // Bloquea clic derecho
        }
    });

    document.getElementById("section_cont_sitios").addEventListener("mousedown", function (e) {
        const target = e.target.closest("a");
        if (target && (e.button === 1 || e.button === 2)) {
            e.preventDefault(); // Bloquea botón medio y derecho
        }
    });

    document.getElementById("section_cont_sitios").addEventListener("mousedown", function (e) {
        const target = e.target.closest("a");
        if (target && (e.button === 1 || e.button === 2)) {
            e.preventDefault(); // Bloquea botón medio (1) y derecho (2)
        }
    });

    document.getElementById("section_cont_sitios").addEventListener("auxclick", function (e) {
        const target = e.target.closest("a");
        if (target && e.button === 1) {
            e.preventDefault(); // Asegura bloqueo del botón del medio (en navegadores que disparan auxclick)
        }
    });
});

function updateEstadoDesafio(id_desafio) {
    setTimeout(() => {
        window.location.reload();
    }, 300);
    $.post("controllers/ctrDesafios.php?case=update_estado_esafio", { id: id_desafio, id_estado: 2 },
        function (data, textStatus, jqXHR) {

        },
        "json"
    );

    $.post("controllers/ctrDesafios.php?case=update_estados_desafios", { id: id_desafio, id_estado: 1 }, //antes id_estado=3
        function (data, textStatus, jqXHR) {

        },
        "json"
    );
}

