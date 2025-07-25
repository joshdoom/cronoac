<?php
// controladores/usuario.controlador.php

class ControladorUsuario {

    public static function ctrIngresoUsuario() {
        if (isset($_POST["ingresoCorreo"])) {
            if (filter_var($_POST["ingresoCorreo"], FILTER_VALIDATE_EMAIL) && !empty($_POST["ingresoPassword"])) {
                $encriptar = hash('sha256', $_POST["ingresoPassword"]);
                $tabla = "usuario";
                $item = "correo";
                $valor = $_POST["ingresoCorreo"];
                $respuesta = ModeloUsuario::mdlMostrarUsuarios($tabla, $item, $valor);
                
                // --- CORRECCIÓN FINAL ---
                // El var_dump mostró que la clave es "Password" (con P mayúscula).
                // Hacemos que la comprobación coincida con esa clave y eliminamos la depuración.
                if ($respuesta && $respuesta["correo"] == $_POST["ingresoCorreo"] && $respuesta["Password"] == $encriptar) {
                    
                    $_SESSION["iniciarSesion"] = "ok";
                    $_SESSION["idUsuario"] = $respuesta["id_usuario"];
                    $_SESSION["correo"] = $respuesta["correo"];
                    $_SESSION["nombre"] = "Usuario"; // Temporal

                    echo '<script> window.location = "index.php?ruta=dashboard"; </script>';
                    exit();

                } else {
                    echo '<br><div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 text-center" role="alert">Error: Correo o contraseña incorrectos.</div>';
                }
            }
        }
    }

    public static function ctrCrearUsuario() {
        if (isset($_POST["registroNombre"])) {
            if (!empty($_POST["registroNombre"]) && !empty($_POST["registroApellido"]) && !empty($_POST["registroCorreo"]) && !empty($_POST["registroPassword"])) {

                $tablaUsuario = "usuario";
                $encriptarPassword = hash('sha256', $_POST["registroPassword"]);

                // Usamos 'password' como la clave en el array.
                $datosUsuario = [
                    "correo" => $_POST["registroCorreo"], 
                    "password" => $encriptarPassword
                ];
                
                $tablaDatosUsuario = "datos_de_usuario";
                $datosPersonales = [
                    "nombre" => $_POST["registroNombre"],
                    "segundo_nombre" => $_POST["registroSegundoNombre"] ?? '',
                    "apellido" => $_POST["registroApellido"],
                    "segundo_apellido" => $_POST["registroSegundoApellido"] ?? '',
                    "cedula_identidad" => $_POST["registroCedula"] ?? ''
                ];

                $respuesta = ModeloUsuario::mdlCrearUsuario($tablaUsuario, $datosUsuario, $tablaDatosUsuario, $datosPersonales);
                
                if ($respuesta == "ok") {
                    echo '<script>
                            alert("¡Registro exitoso! Ahora puedes iniciar sesión.");
                            window.location = "index.php?ruta=login";
                          </script>';
                    exit();
                } else {
                    echo '<br><div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 text-center" role="alert">' . $respuesta . '</div>';
                }
            }
        }
    }
}
