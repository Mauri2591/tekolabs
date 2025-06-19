<?php
$vuln = new Config();
$id_desafio = $_POST['id'] ?? ($_GET['id'] ?? null);
$datos = $id_desafio ? $vuln->get_vulnerabilidad_challenge_x_id($id_desafio) : null;
?>

<body>
    <!-- DESCRIPCIÓN DEL DESAFÍO -->
    <?php if ($datos): ?>
        <span style="color: #33ff33; font-family: monospace;">
            <?php echo nl2br(htmlspecialchars($datos->descripcion)) ?>
        </span>
        <br><br>
    <?php endif; ?>

    <input type="text" style="width: 65%;" id="xss_reflejado" readonly
        value="http://anonymous.com/inicio/?user=#"><br><br>

    <input type="text" id="xss_reflejado_input">
    <i class="text-light" title="<script>alert(XSS)</script>">[?]</i><br>
    <button onclick="simularXSS()">Enviar</button>

    <div id="resultado"
        style="margin-top:20px; width: 20%; margin: 5rem auto; padding:10px; background:gray; border:1px solid #ccc; font-family:monospace; color:yellow">
        Hola >>>
    </div>

    <script>
        function simularXSS() {
            const valorUser = document.getElementById("xss_reflejado_input").value;

            // Arma una URL falsa dinámica como si fuera real
            const baseURL = "http://anonymous.com/inicio/?user=";
            const nuevaURL = baseURL + encodeURIComponent(valorUser);

            // Muestra la URL modificada en el input de arriba
            document.getElementById("xss_reflejado").value = nuevaURL;

            // Refleja el valor ingresado sin sanitizar (XSS reflejado simulado)
            const tmp = document.createElement("div");
            tmp.innerHTML = valorUser;

            // Mostrar el contenido reflejado
            document.getElementById("resultado").innerHTML = `Hola ${tmp.innerHTML}`;

            // Ejecutar cualquier <script> inyectado
            const scripts = tmp.getElementsByTagName("script");
            for (let i = 0; i < scripts.length; i++) {
                try {
                    const contenido = scripts[i].innerText;

                    // Ejecutar el script (ej. alert("XSS"))
                    eval(contenido);

                    // ✅ Marcar desafío como completado (estado 1)
                    $.post("../controllers/ctrDesafios.php?case=update_estados_desafios", {
                        id: ID_DESAFIO,
                        id_estado: 1
                    }, function() {}, "json");

                    clearInterval(intervalo);
                    localStorage.removeItem("expira_desafio");

                    // ✅ Marcar desafío como finalizado (estado 0)
                    $.ajax({
                        type: "POST",
                        url: "../controllers/ctrDesafios.php?case=update_estado_esafio",
                        data: {
                            id: ID_DESAFIO,
                            id_estado: 1 //antes id_estado=0
                        },
                        dataType: "json",
                        success: function() {
                            // Mostrar SweetAlert para el correo
                            Swal.fire({
                                title: '¡Desafío resuelto!',
                                text: 'Ingresá tu correo electrónico para rankearte',
                                input: 'email',
                                inputPlaceholder: 'ejemplo@correo.com',
                                confirmButtonText: 'Enviar',
                                showCancelButton: true,
                                cancelButtonText: 'Cancelar',
                                inputValidator: (value) => !value && '¡Debés ingresar un correo!'
                            }).then((result) => {
                                let email = (result.isConfirmed && result.value) ? result.value :
                                    "ANONIMUS";

                                if (result.isConfirmed) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Correo recibido',
                                        text: `Gracias por participar`,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }

                                $.post("../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos",
                                    function(data) {
                                        let ID_VERSION_PAGINAS_EVENTOS = data.id;

                                        $.post("../controllers/ctrDesafios.php?case=inserar_usuario_desafio", {
                                            usuario: email,
                                            id_desafio: ID_DESAFIO,
                                            id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS,
                                            resolvio: "si"
                                        }, function() {
                                            setTimeout(() => {
                                                window.location.replace(
                                                    '../sessionDestroy.php');
                                            }, 1000);
                                        }, "json");
                                    }, "json");
                            });
                        },
                        error: function(err) {
                            console.error("Error en update_estado_esafio:", err);
                        }
                    });

                } catch (e) {
                    console.error("Error al ejecutar el script:", e);
                }
            }
        }
    </script>
</body>

</html>