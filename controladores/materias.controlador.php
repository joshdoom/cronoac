<?php
// controladores/materia.controlador.php

class ControladorMateria {

    /**
     * Crea una nueva materia para el usuario actual.
     */
    public static function ctrCrearMateria() {
        if (isset($_POST["nuevaMateria"])) {
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/', $_POST["nuevaMateria"])) {
                if (isset($_SESSION["idUsuario"])) {
                    $tabla = "materia";
                    $datos = array(
                        "id_usuario" => $_SESSION["idUsuario"],
                        "nombre_materia" => trim($_POST["nuevaMateria"]) // Eliminar espacios en blanco al inicio/final
                    );
                    $respuesta = ModeloMateria::mdlCrearMateria($tabla, $datos);

                    if ($respuesta == "ok") {
                        echo '<script>
                                Swal.fire({
                                    icon: "success",
                                    title: "¡Materia creada correctamente!",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function(){
                                    window.location = "index.php?ruta=materias";
                                });
                              </script>';
                    } else if (strpos($respuesta, 'Duplicate entry') !== false) {
                         echo '<script>
                                Swal.fire({
                                    icon: "error",
                                    title: "La materia ya existe para este usuario.",
                                    showConfirmButton: true
                                }).then(function(){
                                    window.location = "index.php?ruta=materias";
                                });
                              </script>';
                    }
                    else {
                        echo '<script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Error al crear la materia: ' . $respuesta . '",
                                    showConfirmButton: true
                                }).then(function(){
                                    window.location = "index.php?ruta=materias";
                                });
                              </script>';
                    }
                } else {
                    echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Sesión no iniciada. Por favor, inicia sesión.",
                                showConfirmButton: true
                            }).then(function(){
                                window.location = "index.php?ruta=login";
                            });
                          </script>';
                }
            } else {
                echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "¡El nombre de la materia no puede ir vacío o llevar caracteres especiales!",
                            showConfirmButton: true
                        }).then(function(){
                            window.location = "index.php?ruta=materias";
                        });
                      </script>';
            }
        }
    }

    /**
     * Muestra todas las materias del usuario actual.
     * @return array Un array con las materias del usuario.
     */
    public static function ctrMostrarMaterias() {
        if (isset($_SESSION["idUsuario"])) {
            $tabla = "materia";
            $item = "id_usuario";
            $valor = $_SESSION["idUsuario"];
            $respuesta = ModeloMateria::mdlMostrarMaterias($tabla, $item, $valor);
            return $respuesta;
        } else {
            return []; // Devolver un array vacío si no hay sesión
        }
    }

    /**
     * Edita una materia existente.
     */
    public static function ctrEditarMateria() {
        if (isset($_POST["editarNombreMateria"])) {
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/', $_POST["editarNombreMateria"])) {
                if (isset($_SESSION["idUsuario"]) && isset($_POST["idMateriaEditar"])) {
                    $tabla = "materia";
                    $datos = array(
                        "id_materia" => $_POST["idMateriaEditar"],
                        "nombre_materia" => trim($_POST["editarNombreMateria"]),
                        "id_usuario" => $_SESSION["idUsuario"]
                    );
                    $respuesta = ModeloMateria::mdlEditarMateria($tabla, $datos);

                    if ($respuesta == "ok") {
                        echo '<script>
                                Swal.fire({
                                    icon: "success",
                                    title: "¡Materia actualizada correctamente!",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function(){
                                    window.location = "index.php?ruta=materias";
                                });
                              </script>';
                    } else if (strpos($respuesta, 'Duplicate entry') !== false) {
                         echo '<script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Ya tienes una materia con ese nombre.",
                                    showConfirmButton: true
                                }).then(function(){
                                    window.location = "index.php?ruta=materias";
                                });
                              </script>';
                    }
                    else {
                        echo '<script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Error al actualizar la materia: ' . $respuesta . '",
                                    showConfirmButton: true
                                }).then(function(){
                                    window.location = "index.php?ruta=materias";
                                });
                              </script>';
                    }
                } else {
                    echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Datos insuficientes o sesión no iniciada.",
                                showConfirmButton: true
                            }).then(function(){
                                window.location = "index.php?ruta=materias";
                            });
                          </script>';
                }
            } else {
                echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "¡El nombre de la materia no puede ir vacío o llevar caracteres especiales!",
                            showConfirmButton: true
                        }).then(function(){
                            window.location = "index.php?ruta=materias";
                        });
                      </script>';
            }
        }
    }

    /**
     * Borra una materia.
     */
    public static function ctrBorrarMateria() {
        if (isset($_GET["idMateria"])) {
            if (isset($_SESSION["idUsuario"])) {
                $tabla = "materia";
                $datos = array(
                    "id_materia" => $_GET["idMateria"],
                    "id_usuario" => $_SESSION["idUsuario"]
                );
                $respuesta = ModeloMateria::mdlBorrarMateria($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
                            Swal.fire({
                                icon: "success",
                                title: "¡Materia borrada correctamente!",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function(){
                                window.location = "index.php?ruta=materias";
                            });
                          </script>';
                } else {
                    echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Error al borrar la materia: ' . $respuesta . '",
                                showConfirmButton: true
                            }).then(function(){
                                window.location = "index.php?ruta=materias";
                            });
                          </script>';
                }
            } else {
                echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Sesión no iniciada. No se pudo borrar la materia.",
                            showConfirmButton: true
                        }).then(function(){
                            window.location = "index.php?ruta=login";
                        });
                      </script>';
            }
        }
    }
}