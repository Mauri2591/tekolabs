let URL = "http://127.0.0.1/tekoLabs/";
let intervalo = null;
var resolvio = null

$.post("../controllers/ctrDesafios.php?case=get_ultimo_desafio_activo", { id: ID_DESAFIO }, function (data) {
    let pendiente = data.total_activos;
    let id_pagina = data.id_pagina;
    let id_evento = data.id_evento;

    if (pendiente == "1" || pendiente == 1) {
        // Removemos el reinicio de estado para mantenerlos en estado 1
        console.log("Todos los desafíos pendientes. No se reinicia estado.");
    }
}, "json");

function validarExpiracionGlobal() {
    const key = "expira_desafio";
    const ahora = Date.now();
    const expiracion = parseInt(localStorage.getItem(key));

    if (expiracion && ahora >= expiracion) {
        localStorage.removeItem(key);
        Swal.fire({
            icon: "info",
            title: "Tiempo agotado",
            text: "Tu sesión ha expirado.",
            timer: 1500,
            showConfirmButton: false
        }).then(() => window.location.replace("../cerrar_sesion.php"));
    }
}


document.addEventListener("DOMContentLoaded", function () {
    if (typeof ID_DESAFIO === "undefined" || !ID_DESAFIO) {
        Swal.fire({ icon: "info", title: "Challenge no disponible", timer: 1500, showConfirmButton: false })
            .then(() => window.location.replace("../cerrar_sesion.php"));
        return;
    }

    validarExpiracionGlobal();

    $.post("../controllers/ctrDesafios.php?case=get_chellenges_pagina_x_desafio_id", { id: ID_DESAFIO }, function (data) {
        if (data.leyenda) $("#cont_leyenda_challenge").html(data.leyenda);
    }, "json");

    $.post("../controllers/ctrDesafios.php?case=get_vulnerabilidad_challenge_x_id", {
        id: ID_DESAFIO,
        rol: $("input[name='rol']").val()
    }, function (data) {
        $(".contenedor_challenges_desafios").html(data);
    });

    $.post("../controllers/ctrDesafios.php?case=get_desafio_x_id", { id: ID_DESAFIO },
        function (data, textStatus, jqXHR) {
            $("#desafio_cabecera").html(data)
        },
        "html"
    );

    // Esta es la sección que debés reemplazar COMPLETAMENTE en tu archivo actual (dentro de la función document.addEventListener)

    $.post("../controllers/ctrDesafios.php?case=get_nivel_desafio", { id: ID_DESAFIO }, function (data) {
        const nivel = data.id_nivel;
        let duracionTotal = [30, 60, 90][nivel - 1] || 100;
        const key = "expira_desafio";
        const ahora = Date.now();

        const resuelto = localStorage.getItem("resuelto_" + ID_DESAFIO);
        let expiracionGuardada = localStorage.getItem(key);

        if (resuelto) {
            // El usuario ya resolvió el desafio, se reinicia el reloj
            localStorage.setItem(key, ahora + duracionTotal * 1000);
            localStorage.removeItem("resuelto_" + ID_DESAFIO);
            expiracionGuardada = localStorage.getItem(key); // actualizo para el flujo siguiente
        }

        let tiempoRestante = expiracionGuardada
            ? Math.floor((parseInt(expiracionGuardada) - ahora) / 1000)
            : duracionTotal;

        if (tiempoRestante <= 0) {
            localStorage.removeItem(key);
            location.reload();
            return;
        }

        if (!expiracionGuardada) {
            localStorage.setItem(key, ahora + duracionTotal * 1000);
        }

        const reloj = document.getElementById("reloj_regresivo");
        reloj.textContent = tiempoRestante;

        intervalo = setInterval(() => {
            tiempoRestante--;
            reloj.textContent = tiempoRestante;
            if (tiempoRestante <= 0) {
                clearInterval(intervalo);
                localStorage.removeItem(key);

                $.post("../controllers/ctrDesafios.php?case=update_estado_esafio", { id: ID_DESAFIO, id_estado: 1 });

                Swal.fire({
                    icon: "info",
                    title: "Tiempo agotado",
                    text: "No lo superaste. Intenta de nuevo!",
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => window.location.replace("../cerrar_sesion.php"));
            }
        }, 1000);
    }, "json");

    // ADEMÁS, agregá esta línea DENTRO de funcionEnviarRespuesta() en AMBOS casos:
    // Justo antes del setTimeout que redirige al usuario

});


function funcionEnviarRespuesta() {
    $.post("../controllers/ctrDesafios.php?case=get_solucion_vulnerabilidad_challenge_x_id", { id: ID_DESAFIO }, function (data) {
        let SOLUCION = data.solucion.toLowerCase();
        let respuestaUsuario = $("#inputSolucion").val()?.trim().toLowerCase() ||
            document.querySelector('input[name="respuesta"]:checked')?.value.trim().toLowerCase();

        if (respuestaUsuario === SOLUCION) {
            clearInterval(intervalo);
            localStorage.removeItem("expira_desafio");

            $.post("../controllers/ctrDesafios.php?case=update_estado_esafio", { id: ID_DESAFIO, id_estado: 1 }); //antes id_estado=0
            $.post("../controllers/ctrDesafios.php?case=update_estados_desafios", { id: ID_DESAFIO, id_estado: 1 });

            Swal.fire({
                title: 'Desafío resuelto',
                text: 'Ingresá tu correo electrónico para rankearte',
                input: 'email',
                inputPlaceholder: 'ejemplo@correo.com',
                inputValue: '',
                confirmButtonText: 'Enviar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    // Si querés que sea opcional, no devuelvas nada
                    return null;
                }
            }).then((result) => {
                // Si cancela o deja el campo vacío, guardamos null
                let email = (result.isConfirmed && result.value) ? result.value : "ANONIMUS";

                Swal.fire({
                    icon: 'success',
                    title: 'Finalizado',
                    text: 'Gracias por participar',
                    timer: 2000,
                    showConfirmButton: false
                });

                localStorage.setItem("resuelto_" + ID_DESAFIO, "1");

                $.post("../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos", function (data) {
                    let ID_VERSION_PAGINAS_EVENTOS = data.id;

                    $.post("../controllers/ctrDesafios.php?case=inserar_usuario_desafio", {
                        usuario: email,
                        id_desafio: ID_DESAFIO,
                        id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS,
                        resolvio: "si"
                    }, function () {
                        setTimeout(() => {
                            window.location.replace('../sessionDestroy.php');
                        }, 1500);
                    });
                }, "json");
            });

        }

        else {
            clearInterval(intervalo);
            localStorage.removeItem("expira_desafio");

            $.post("../controllers/ctrDesafios.php?case=update_estado_esafio", { id: ID_DESAFIO, id_estado: 1 });

            Swal.fire({
                icon: 'warning',
                title: 'Respuesta incorrecta',
                text: 'No acertaste. Ingresá tu correo para registrar tu participación.',
                input: 'email',
                inputPlaceholder: 'ejemplo@correo.com',
                confirmButtonText: 'Enviar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => null
            }).then((result) => {
                let email = (result.isConfirmed && result.value) ? result.value : "ANONIMUS";

                Swal.fire({
                    icon: 'info',
                    title: 'Participación registrada',
                    text: 'Gracias por intentarlo.',
                    timer: 1500,
                    showConfirmButton: false
                });

                $.post("../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos", function (data) {
                    let ID_VERSION_PAGINAS_EVENTOS = data.id;

                    $.post("../controllers/ctrDesafios.php?case=inserar_usuario_desafio", {
                        usuario: email,
                        id_desafio: ID_DESAFIO,
                        id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS,
                        resolvio: null
                    }, function () {
                        setTimeout(() => {
                            window.location.replace('../sessionDestroy.php');
                        }, 1500);
                    });

                }, "json");
            });
        }

    }, "json");
}

function refresh() {
    window.location.reload();
}

function enviarPayload() {
    const valor = $("#rol_input").val().trim();
    if (!valor) return;

    $.post("../controllers/ctrDesafios.php?case=get_vulnerabilidad_challenge_x_id", { id: ID_DESAFIO, rol: valor }, function (data) {
        $(".contenedor_challenges_desafios").html(data);
    }, "html");
}


//****************  inicio consultarArchivo    ******************** */
function consultarArchivo() {
    const valor = document.getElementById("input_env").value.trim().toLowerCase();
    const contenedor = document.getElementById("resultado_env");

    if (valor === '.env') {
        $.post("../controllers/ctrDesafios.php?case=get_solucion_vulnerabilidad_challenge_x_id", { id: ID_DESAFIO },
            function (data) {
                let solucion = data.solucion ?? 'no_definida';
                SOLUCION_GLOBAL = data.solucion.toLowerCase();
                contenedor.innerHTML = `<pre style="color:yellow; font-family: monospace;">${`
APP_ENV=production
APP_DEBUG=true
APP_KEY=base64:...eliminado...
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ctf_lab
DB_USERNAME=root
DB_PASSWORD=${solucion}
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=apiuser
MAIL_PASSWORD=${solucion}`.replace(/</g, '&lt;')}</pre>`;
            }, "json");
    } else {
        contenedor.innerHTML = `<span style="color:red;">Archivo no encontrado o nombre incorrecto</span>`;
    }
    return false;
}
function validarRespuesta() {
    const valor = document.getElementById("inputSolucion").value.trim().toLowerCase();

    if (!SOLUCION_GLOBAL) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'La solución no está disponible. Recargá la página.', timer: 1500, showConfirmButton: false });
        return;
    }

    if (valor === SOLUCION_GLOBAL) {
        clearInterval(intervalo);
        localStorage.removeItem("expira_desafio");

        $.post("../controllers/ctrDesafios.php?case=update_estado_esafio", { id: ID_DESAFIO, id_estado: 1 }, function () { //antes id_estado=0

            Swal.fire({
                title: 'Desafío resuelto',
                text: 'Ingresá tu correo electrónico para rankearte',
                input: 'email',
                inputPlaceholder: 'ejemplo@correo.com',
                inputValue: '',
                confirmButtonText: 'Enviar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    // Si querés que sea opcional, no devuelvas nada
                    return null;
                }
            }).then((result) => {
                // Si cancela o deja el campo vacío, guardamos null
                let email = (result.isConfirmed && result.value) ? result.value : "ANONIMUS";

                Swal.fire({
                    icon: 'success',
                    title: 'Finalizado',
                    text: 'Gracias por participar',
                    timer: 2000,
                    showConfirmButton: false
                });

                $.post("../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos", function (data) {
                    let ID_VERSION_PAGINAS_EVENTOS = data.id;

                    $.post("../controllers/ctrDesafios.php?case=inserar_usuario_desafio", {
                        usuario: email,
                        id_desafio: ID_DESAFIO,
                        id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS,
                        resolvio: "si"
                    }, function () {
                        setTimeout(() => {
                            window.location.replace('../sessionDestroy.php');
                        }, 1500);
                    });
                }, "json");
            });
        });
    }

    else {
        Swal.fire({ icon: 'error', title: 'Respuesta incorrecta', text: 'Intentá de nuevo.', timer: 1500, showConfirmButton: false });
    }
}
//****************  fin consultarArchivo   ******************** */



//****************  inicio CurlEnvioPeticionHeaderYBody    ******************** */
function consultarCurlEnvioPeticionHeaderYBody() {
    const valor = document.getElementById("curl").value.trim();
    const contenedor = document.getElementById("resultadoCurlEnvioPeticionHeaderYBody");

    const valores_validos = [`curl -H "Content-type: application/json" -d '{"user":"all"}' http://anonymous.com/user/`];

    if (valores_validos.includes(valor)) {
        $.post("../controllers/ctrDesafios.php?case=get_solucion_vulnerabilidad_challenge_x_id", { id: ID_DESAFIO }, function (data) {
            let solucion = SOLUCION_GLOBAL ?? 'shellshock';
            SOLUCION_GLOBAL = solucion.toLowerCase();
            contenedor.innerHTML = `<pre style="color:yellow; font-family: monospace;"><br>${`
[ { "id": 1, "username": "roocky", "rol": "user" },{ "id": 2, "username": "shellshock", "rol": "user" },{ "id": 3, "username": "scoobydoo", "rol": "user" } ]`.replace(/</g, '&lt;')}</pre>`;
        }, "json");
    } else {
        contenedor.innerHTML = `<span style="color:red;">Curl incorrecto. Verifica los parámetros!!</span>`;
    }
}
function validarRespuestaConsultarCurlEnvioPeticionHeaderYBody() {
    const valor = document.getElementById("inputSolucion").value.trim().toLowerCase();

    if (!SOLUCION_GLOBAL) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La solución no está disponible. Recargá la página.',
            timer: 1500,
            showConfirmButton: false
        });
        return;
    }

    if (valor === SOLUCION_GLOBAL) {
        $.post("../controllers/ctrDesafios.php?case=update_estado_esafio", { id: ID_DESAFIO, id_estado: 1 }, function () {//antes id_estado=0
            Swal.fire({
                title: 'Desafío resuelto',
                text: 'Ingresá tu correo electrónico para rankearte',
                input: 'email',
                inputPlaceholder: 'ejemplo@correo.com',
                inputValue: '',
                confirmButtonText: 'Enviar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    // Si querés que sea opcional, no devuelvas nada
                    return null;
                }
            }).then((result) => {
                // Si cancela o deja el campo vacío, guardamos null
                let email = (result.isConfirmed && result.value) ? result.value : "ANONIMUS";

                Swal.fire({
                    icon: 'success',
                    title: 'Finalizado',
                    text: 'Gracias por participar',
                    timer: 2000,
                    showConfirmButton: false
                });

                $.post("../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos", function (data) {
                    let ID_VERSION_PAGINAS_EVENTOS = data.id;

                    $.post("../controllers/ctrDesafios.php?case=inserar_usuario_desafio", {
                        usuario: email,
                        id_desafio: ID_DESAFIO,
                        id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS,
                        resolvio: "si"
                    }, function () {
                        setTimeout(() => {
                            window.location.replace('../sessionDestroy.php');
                        }, 1500);
                    });
                }, "json");
            });
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Respuesta incorrecta',
            text: 'Intentá de nuevo.',
            timer: 1500,
            showConfirmButton: false
        });
    }

}
//****************  fin RespuestaConsultarCurlEnvioPeticionHeaderYBody   ******************** */



// ************* inicio CurlObtenerPassAdmin  ***********
function consultarCurlObtenerPassAdmin() {
    const contenedor = document.getElementById("resultadoCurlObtenerPassAdmin");
    const input = document.getElementById("CurlObtenerPassAdmin");
    const valor = input.value.trim();

    const regex = /^curl\s+(?:-H|--header)\s+"content-type:\s*application\/json"\s+(?:-d|--data)\s+'{"user":"all","rol":"admin"}'\s+http:\/\/anonymous\.com\/user\/?$/i;

    if (regex.test(valor)) {
        $.post("../controllers/ctrDesafios.php?case=get_solucion_vulnerabilidad_challenge_x_id", { id: ID_DESAFIO }, function (data) {
            let solucion = data.solucion ?? 'no_definida';
            SOLUCION_GLOBAL = solucion.toLowerCase();
            contenedor.innerHTML = `<pre style="color:yellow; font-family: monospace;">${`[ { "user_id": 1, "username": "admin", "pass": "${solucion}" } ]`.replace(/</g, '&lt;')}</pre>`;
        }, "json");
    } else {
        contenedor.innerHTML = `<span style="color:red;">Curl inválido. Recordá usar -H o --header, con JSON bien formado.</span>`;
    }
}
function validarRespuestaCurlObtenerPassAdmin() {
    const valor = document.getElementById("inputSolucion").value.trim().toLowerCase();

    if (!SOLUCION_GLOBAL) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La solución no está disponible. Recargá la página.',
            timer: 1500,
            showConfirmButton: false
        });
        return;
    }

    if (valor === SOLUCION_GLOBAL) {
        $.post("../controllers/ctrDesafios.php?case=update_estado_esafio", { id: ID_DESAFIO, id_estado: 1 }, function () {//antes id_estado=0
            Swal.fire({
                title: 'Desafío resuelto',
                text: 'Ingresá tu correo electrónico para rankearte',
                input: 'email',
                inputPlaceholder: 'ejemplo@correo.com',
                inputValue: '',
                confirmButtonText: 'Enviar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    // Si querés que sea opcional, no devuelvas nada
                    return null;
                }
            }).then((result) => {
                // Si cancela o deja el campo vacío, guardamos null
                let email = (result.isConfirmed && result.value) ? result.value : "ANONIMUS";

                Swal.fire({
                    icon: 'success',
                    title: 'Finalizado',
                    text: 'Gracias por participar',
                    timer: 2000,
                    showConfirmButton: false
                });

                $.post("../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos", function (data) {
                    let ID_VERSION_PAGINAS_EVENTOS = data.id;

                    $.post("../controllers/ctrDesafios.php?case=inserar_usuario_desafio", {
                        usuario: email,
                        id_desafio: ID_DESAFIO,
                        id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS,
                        resolvio: "si"
                    }, function () {
                        setTimeout(() => {
                            window.location.replace('../sessionDestroy.php');
                        }, 1500);
                    });
                }, "json");
            });
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Respuesta incorrecta',
            text: 'Intentá de nuevo.',
            timer: 1500,
            showConfirmButton: false
        });
    }
}
//****************  fin RespuestaCurlObtenerPassAdmin  ******************** */




// ************* inicio Path Traversal  ***********
function consultarPathTraversal() {
    const payload = document.getElementById("pathTraversal").value.trim();
    const resultadoDiv = document.getElementById("resultadoPathTraversal");

    // Mostrar la URL simulada
    document.getElementById("url_path_traversal").placeholder = "http://anonymous.com/perfiles/?file=" + payload;

    // Payload correcto simulado
    const payloadEsperado = "../../../../../../etc/passwd";

    // Contenido simulado de /etc/passwd
    const contenidoPasswd = `<pre style="color:yellow; font-family: monospace;">
root:x:0:0:root:/root:/bin/bash
daemon:x:1:1:daemon:/usr/sbin:/usr/sbin/nologin
bin:x:2:2:bin:/bin:/usr/sbin/nologin
sshuser:x:1002:1002:SSH User:/home/sshuser:/bin/bash
ctf:x:1001:1001:CTF Player:/home/ctf:/bin/bash
</pre>`;

    if (payload === payloadEsperado) {
        resultadoDiv.innerHTML = "<pre style='background:#222;color:#33ff33;padding:10px;'>" + contenidoPasswd + "</pre>";
    } else {
        resultadoDiv.innerHTML = "<span style='color:red;'>Archivo no encontrado o acceso denegado.</span>";
    }
}
function validarRespuestaPathTraversal() {
    const valor = document.getElementById("inputSolucion").value.trim().toLowerCase();
    if (valor === "3") {
        clearInterval(intervalo);
        localStorage.removeItem("expira_desafio");
        $.post("../controllers/ctrDesafios.php?case=update_estado_esafio", { id: ID_DESAFIO, id_estado: 1 }, function () {//antes id_estado=0
            Swal.fire({
                title: 'Desafío resuelto',
                text: 'Ingresá tu correo electrónico para rankearte',
                input: 'email',
                inputPlaceholder: 'ejemplo@correo.com',
                inputValue: '',
                confirmButtonText: 'Enviar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    // Si querés que sea opcional, no devuelvas nada
                    return null;
                }
            }).then((result) => {
                // Si cancela o deja el campo vacío, guardamos null
                let email = (result.isConfirmed && result.value) ? result.value : "ANONIMUS";

                Swal.fire({
                    icon: 'success',
                    title: 'Finalizado',
                    text: 'Gracias por participar',
                    timer: 2000,
                    showConfirmButton: false
                });

                $.post("../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos", function (data) {
                    let ID_VERSION_PAGINAS_EVENTOS = data.id;

                    $.post("../controllers/ctrDesafios.php?case=inserar_usuario_desafio", {
                        usuario: email,
                        id_desafio: ID_DESAFIO,
                        id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS,
                        resolvio: "si"
                    }, function () {
                        setTimeout(() => {
                            window.location.replace('../sessionDestroy.php');
                        }, 1500);
                    });
                }, "json");
            });
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Respuesta incorrecta',
            text: 'Intentá de nuevo.',
            timer: 1500,
            showConfirmButton: false
        });
    }
}
//****************  fin Path Traversal   ******************** */




// ************* inicio Wget Sitio  ***********
function consultarWgetSitio() {
    const valor = document.getElementById("wget").value.trim().replace(/\s+/g, ' ');

    const contenedor = document.getElementById("resultadoWgetSitio");

    const valores_validos = [
        'wget http://telecom.com/ethicalHacking/',
        'wget https://telecom.com/ethicalHacking/'
    ];

    if (valores_validos.includes(valor)) {
        $.post("../controllers/ctrDesafios.php?case=get_solucion_vulnerabilidad_challenge_x_id", { id: ID_DESAFIO }, function (data) {
            let solucion = SOLUCION_GLOBAL;
            SOLUCION_GLOBAL = solucion.toLowerCase();
            contenedor.innerHTML = `<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body
    style="background-color: #0d1117; color: #c9d1d9; font-family: 'Courier New', Courier, monospace; margin: 0; padding: 2rem;">
    
    <main
        style="max-width: 800px; margin: 2rem auto; background-color: #161b22; padding: 2rem; border-radius: 8px; box-shadow: 0 0 10px rgba(88, 166, 255, 0.2);">
    <input disabled style="width:100%; margin-bottom:2rem; background-color:#c9d1d9; text-align: center;"
        value="https://telecom.com/ethicalHacking/">
        <h2 style="color: #58a6ff; font-size: 2rem; text-align: center;">Bienvenido al entorno del hacker ético</h2>
        <p>Este sitio ha sido generado por el equipo de <strong>Ethical Hacking de Telecom Argentina</strong>.</p>
        <p>Su propósito es exclusivamente educativo y forma parte de una plataforma de concientización en
            ciberseguridad.</p>
        <p>Te dejamos un regalo, copialo!!
            <span style="width: 50%;">
                <marquee style="color:yellow" behavior="" direction="">R0FOQVNURQ</marquee>
            </span>
        </p>
        <p><em>Toda actividad realizada será monitoreada y registrada.</em></p>
        <footer style="text-align: center; margin-top: 2rem; font-size: 0.9rem; color: #8b949e;">
            &copy; 2025 Telecom Argentina - Ethical Hacking Team
        </footer>
    </main>
</body>

</html>
`;
        });
    } else {
        contenedor.innerHTML = `<span style="color:red;">wget incorrecto. Verifica los parámetros!!</span>`;
    }
}
function validarRespuestaWgetSitio() {
    const valor = document.getElementById("inputSolucion").value.trim().toLowerCase();

    if (!SOLUCION_GLOBAL) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La solución no está disponible. Recargá la página.',
            timer: 1500,
            showConfirmButton: false
        });
        return;
    }

    if (valor === SOLUCION_GLOBAL) {
        clearInterval(intervalo);
        localStorage.removeItem("expira_desafio");
        $.post("../controllers/ctrDesafios.php?case=update_estado_esafio", { id: ID_DESAFIO, id_estado: 1 }, function () {//antes id_estado=0
            Swal.fire({
                title: 'Desafío resuelto',
                text: 'Ingresá tu correo electrónico para rankearte',
                input: 'email',
                confirmButtonText: 'Enviar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    // Si querés que sea opcional, no devuelvas nada
                    return null;
                }
            }).then((result) => {
                let email = (result.isConfirmed && result.value) ? result.value : "ANONIMUS";

                Swal.fire({
                    icon: 'success',
                    title: 'Finalizado',
                    text: 'Gracias por participar',
                    timer: 2000,
                    showConfirmButton: false
                });

                $.post("../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos", function (data) {
                    let ID_VERSION_PAGINAS_EVENTOS = data.id;

                    $.post("../controllers/ctrDesafios.php?case=inserar_usuario_desafio", {
                        usuario: email,
                        id_desafio: ID_DESAFIO,
                        id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS,
                        resolvio: "si"
                    }, function () {
                        setTimeout(() => {
                            window.location.replace('../sessionDestroy.php');
                        }, 1500);
                    });
                }, "json");
            });

        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Respuesta incorrecta',
            text: 'Intentá de nuevo.',
            timer: 1500,
            showConfirmButton: false
        });
    }

}
//****************  fin Wget Sitio   ******************** */



//****************  inicio RespuestaRceBasico    ******************** */
function consultarRceBasico() {
    const valor = document.getElementById("rce_basico").value.trim();
    const contenedor = document.getElementById("resultado_rce_basico");

    const valores_validos = ['whoami', 'ls', 'id', 'groups'];

    if (valores_validos.includes(valor)) {
        $.post("../controllers/ctrDesafios.php?case=get_solucion_vulnerabilidad_challenge_x_id", { id: ID_DESAFIO }, function (data) {
            let solucion = SOLUCION_GLOBAL ?? 'shellshock';
            SOLUCION_GLOBAL = solucion.toLowerCase();

            let salida = '';
            switch (valor) {
                case 'ls':
                    salida = `index.php\nconfig.php\nlogs.txt`;
                    break;
                case 'id':
                    salida = `uid=33(www-data) gid=33(www-data) groups=33(www-data)`;
                    break;
                case 'groups':
                    salida = `www-data`;
                    break;
                case 'whoami':
                    salida = `www-data`;
                    break;
            }
            contenedor.innerHTML = `<pre style="color:yellow; font-family: monospace;">${salida}</pre>`;
        }, "json");
    } else {
        contenedor.innerHTML = `<span style="color:red;">Comando incorrecto. Verifica los parámetros!!</span>`;
    }
}
function validarRespuestaRceBasico() {
    const valor = document.getElementById("inputSolucion").value.trim().toLowerCase();

    if (!SOLUCION_GLOBAL) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La solución no está disponible. Recargá la página.',
            timer: 1500,
            showConfirmButton: false
        });
        return;
    }

    if (valor === SOLUCION_GLOBAL) {
        $.post("../controllers/ctrDesafios.php?case=update_estado_esafio", { id: ID_DESAFIO, id_estado: 1 }, function () {//antes id_estado=0
            Swal.fire({
                title: 'Desafío resuelto',
                text: 'Ingresá tu correo electrónico para rankearte',
                input: 'email',
                inputPlaceholder: 'ejemplo@correo.com',
                inputValue: '',
                confirmButtonText: 'Enviar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    // Si querés que sea opcional, no devuelvas nada
                    return null;
                }
            }).then((result) => {
                // Si cancela o deja el campo vacío, guardamos null
                let email = (result.isConfirmed && result.value) ? result.value : "ANONIMUS";

                Swal.fire({
                    icon: 'success',
                    title: 'Finalizado',
                    text: 'Gracias por participar',
                    timer: 2000,
                    showConfirmButton: false
                });

                $.post("../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos", function (data) {
                    let ID_VERSION_PAGINAS_EVENTOS = data.id;

                    $.post("../controllers/ctrDesafios.php?case=inserar_usuario_desafio", {
                        usuario: email,
                        id_desafio: ID_DESAFIO,
                        id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS,
                        resolvio: "si"
                    }, function () {
                        setTimeout(() => {
                            window.location.replace('../sessionDestroy.php');
                        }, 1500);
                    });
                }, "json");
            });
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Respuesta incorrecta',
            text: 'Intentá de nuevo.',
            timer: 1500,
            showConfirmButton: false
        });
    }

}
//****************  fin RespuestaRceBasico   ******************** */



//****************  inicio RespuestaIngresarValorCookie    ******************** */
function consultarValorCookie() {
    const valor = document.getElementById("obtener_cookie").value.trim();
    const valor_valido = 'document.cookie';
    const contenedor = document.getElementById("resultado_obtener_cookie");

    if (valor == valor_valido) {
        $.post("../controllers/ctrDesafios.php?case=get_solucion_vulnerabilidad_challenge_x_id", { id: ID_DESAFIO }, function (data) {
            // let salida = 'Cookie=ewogICJzZXNzaW9uSUQiOiAiMTIzNDUiLAogICJ1c2VybmFtZSI6ICJtYXVyaWNpbyIsCiAgImlzQWRtaW4iOiB0cnVlCn0';
            let solucion = SOLUCION_GLOBAL.trim() ?? 'ewogICJzZXNzaW9uSUQiOiAiMTIzNDUiLAogICJ1c2VybmFtZSI6ICJ0ZWxlY29tIiwKICAiaXNBZG1pbiI6IGZhbHNlCn0';
            contenedor.innerHTML = `<span style="color:yellow;">Cookie:${solucion}</span>`;
        }, "json");
    } else {
        contenedor.innerHTML = `<span style="color:red;">Comando incorrecto. Verifica los parámetros!!</span>`;
    }
}
function validarRespuestaIngresarValorCookie() {
    const valor = document.getElementById("inputSolucion").value.trim();

    if (valor === SOLUCION_GLOBAL || valor === 'ewogICJzZXNzaW9uSUQiOiAiMTIzNDUiLAogICJ1c2VybmFtZSI6ICJ0ZWxlY29tIiwKICAiaXNBZG1pbiI6IGZhbHNlCn0') {
        $.post("../controllers/ctrDesafios.php?case=update_estado_esafio", { id: ID_DESAFIO, id_estado: 1 }, function () {//antes id_estado=0
            Swal.fire({
                title: 'Desafío resuelto',
                text: 'Ingresá tu correo electrónico para rankearte',
                input: 'email',
                inputPlaceholder: 'ejemplo@correo.com',
                inputValue: '',
                confirmButtonText: 'Enviar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    // Si querés que sea opcional, no devuelvas nada
                    return null;
                }
            }).then((result) => {
                // Si cancela o deja el campo vacío, guardamos null
                let email = (result.isConfirmed && result.value) ? result.value : "ANONIMUS";

                Swal.fire({
                    icon: 'success',
                    title: 'Finalizado',
                    text: 'Gracias por participar',
                    timer: 2000,
                    showConfirmButton: false
                });

                $.post("../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos", function (data) {
                    let ID_VERSION_PAGINAS_EVENTOS = data.id;

                    $.post("../controllers/ctrDesafios.php?case=inserar_usuario_desafio", {
                        usuario: email,
                        id_desafio: ID_DESAFIO,
                        id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS,
                        resolvio: "si"
                    }, function () {
                        setTimeout(() => {
                            window.location.replace('../sessionDestroy.php');
                        }, 1500);
                    });
                }, "json");
            });
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Respuesta incorrecta',
            timer: 1500,
            showConfirmButton: false
        });
    }

}
//****************  fin RespuestaIngresarValorCookie   ******************** */



//****************  inicio RespuestaIngresarValorCookieModificada    ******************** */
function consultarValorCookieModificada() {
    const valor = document.getElementById("obtener_cookie_modificada").value.trim();
    const valor_valido = 'document.cookie';
    const contenedor = document.getElementById("resultado_obtener_cookie_modificada");
    const valor_cookie_original = 'ewogICJzZXNzaW9uSUQiOiAiMTIzNDUiLAogICJ1c2VybmFtZSI6ICJ0ZWxlY29tIiwKICAiaXNBZG1pbiI6IGZhbHNlCn0';

    if (valor == valor_valido) {
        $.post("../controllers/ctrDesafios.php?case=get_solucion_vulnerabilidad_challenge_x_id", { id: ID_DESAFIO }, function (data) {
            contenedor.innerHTML = `<span style="color:yellow;">Cookie:ewogICJzZXNzaW9uSUQiOiAiMTIzNDUiLAogICJ1c2VybmFtZSI6ICJ0ZWxlY29tIiwKICAiaXNBZG1pbiI6IGZhbHNlCn0</span>`;
        }, "json");
    } else {
        contenedor.innerHTML = `<span style="color:red;">Comando incorrecto. Verifica los parámetros!!</span>`;
    }
}
function validarRespuestaValorCookieModificada() {
    const valor = document.getElementById("inputSolucion").value.trim();

    if (valor === SOLUCION_GLOBAL || valor === 'eyAgICJzZXNzaW9uSUQiOiAiMTIzNDUiLCAgICJ1c2VybmFtZSI6ICJ0ZWxlY29tIiwgICAiaXNBZG1pbiI6IHRydWUgfQ==' || valor == 'eyAgICJzZXNzaW9uSUQiOiAiMTIzNDUiLCAgICJ1c2VybmFtZSI6ICJ0ZWxlY29tIiwgICAiaXNBZG1pbiI6IHRydWUgfQ=' || valor == 'eyAgICJzZXNzaW9uSUQiOiAiMTIzNDUiLCAgICJ1c2VybmFtZSI6ICJ0ZWxlY29tIiwgICAiaXNBZG1pbiI6IHRydWUgfQ') {
        $.post("../controllers/ctrDesafios.php?case=update_estado_esafio", { id: ID_DESAFIO, id_estado: 1 }, function () {//antes id_estado=0
            Swal.fire({
                title: 'Desafío resuelto',
                text: 'Ingresá tu correo electrónico para rankearte',
                input: 'email',
                inputPlaceholder: 'ejemplo@correo.com',
                inputValue: '',
                confirmButtonText: 'Enviar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    // Si querés que sea opcional, no devuelvas nada
                    return null;
                }
            }).then((result) => {
                // Si cancela o deja el campo vacío, guardamos null
                let email = (result.isConfirmed && result.value) ? result.value : "ANONIMUS";

                Swal.fire({
                    icon: 'success',
                    title: 'Finalizado',
                    text: 'Gracias por participar',
                    timer: 2000,
                    showConfirmButton: false
                });

                $.post("../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos", function (data) {
                    let ID_VERSION_PAGINAS_EVENTOS = data.id;

                    $.post("../controllers/ctrDesafios.php?case=inserar_usuario_desafio", {
                        usuario: email,
                        id_desafio: ID_DESAFIO,
                        id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS,
                        resolvio: "si"
                    }, function () {
                        setTimeout(() => {
                            window.location.replace('../sessionDestroy.php');
                        }, 1500);
                    });
                }, "json");
            });
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Respuesta incorrecta',
            timer: 1500,
            showConfirmButton: false
        });
    }
}
function idOptionCodificar(value) {
    document.getElementById("input_cod_enc_hidden").value = value;
    document.getElementById("input_cod_enc").value = "";
    document.getElementById("textarea_cod_enc").value = "";
}
function input_cod_enc() {
    const modo = document.getElementById("input_cod_enc_hidden").value;
    const input = document.getElementById("input_cod_enc").value;
    const textarea = document.getElementById("textarea_cod_enc");

    try {
        let resultado = "";
        if (modo === "1") {
            resultado = btoa(input); // Codifica input
        } else if (modo === "2") {
            resultado = atob(input); // Decodifica input
        }
        textarea.value = resultado;
    } catch (e) {
        textarea.value = "⚠️ Error: texto inválido para decodificar.";
    }
}

function mostrarDecoder() {
    if (document.getElementById("contenedor_decorer").style.display == "none") {
        document.getElementById("contenedor_decorer").style.display = "block"
    } else {
        document.getElementById("contenedor_decorer").style.display = "none"
    }
}
//************** fin RespuestaIngresarValorCookieModificada ******************** */



document.getElementById("btnCancelarChallenge").addEventListener("click", function () {
    localStorage.removeItem("expira_desafio");
    $.post("../controllers/ctrDesafios.php?case=update_estado_esafio", { id: ID_DESAFIO, id_estado: 1 });
    Swal.fire({
        icon: "info",
        title: "Challenge cancelado",
        text: "Puedes continuar con otro..",
        timer: 1500,
        showConfirmButton: false
    }).then(() => window.location.replace("../cerrar_sesion.php"));
})