<?php
// controladores/actividad.controlador.php

class ControladorActividad {

    /**
     * Crea una nueva actividad.
     */
    public static function ctrCrearActividad() {
        if (isset($_POST["nuevaActividadObjetivo"])) {
            if (!empty($_POST["nuevaActividadObjetivo"]) && !empty($_POST["idMateriaActividad"]) && !empty($_POST["nuevaActividadFecha"])) {
                if (isset($_SESSION["idUsuario"])) {
                    $tabla = "actividades";
                    $datos = array(
                        "id_usuario" => $_SESSION["idUsuario"],
                        "id_materia" => $_POST["idMateriaActividad"],
                        "objetivo" => trim($_POST["nuevaActividadObjetivo"]),
                        "tipo" => $_POST["nuevaActividadTipo"] ?? 'Tarea', // Default value if not set
                        "fecha_entrega" => $_POST["nuevaActividadFecha"],
                        "valor" => !empty($_POST["nuevaActividadValor"]) ? $_POST["nuevaActividadValor"] : null,
                        "estado" => $_POST["nuevaActividadEstado"] ?? 'Pendiente', // Default value
                        "nota" => !empty($_POST["nuevaActividadNota"]) ? $_POST["nuevaActividadNota"] : null
                    );

                    $respuesta = ModeloActividad::mdlCrearActividad($tabla, $datos);

                    if ($respuesta == "ok") {
                        echo '<script>
                                Swal.fire({
                                    icon: "success",
                                    title: "¡Actividad creada correctamente!",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function(){
                                    window.location = "index.php?ruta=actividades";
                                });
                              </script>';
                    } else {
                        echo '<script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Error al crear la actividad: ' . $respuesta . '",
                                    showConfirmButton: true
                                }).then(function(){
                                    window.location = "index.php?ruta=actividades";
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
                            title: "¡Los campos obligatorios de la actividad no pueden ir vacíos!",
                            showConfirmButton: true
                        }).then(function(){
                            window.location = "index.php?ruta=actividades";
                        });
                      </script>';
            }
        }
    }

    /**
     * Muestra las actividades del usuario actual, con opciones de filtro.
     * @param string $filtroEstado Filtra por estado (ej. "Pendiente", "Completada").
     * @param string $filtroMateria Filtra por id_materia.
     * @return array Un array con las actividades del usuario.
     */
    public static function ctrMostrarActividades($filtroEstado = null, $filtroMateria = null) {
        if (isset($_SESSION["idUsuario"])) {
            $tabla = "actividades";
            $id_usuario = $_SESSION["idUsuario"];
            $condiciones = ["id_usuario" => $id_usuario];

            if ($filtroEstado && $filtroEstado != "all") {
                $condiciones["estado"] = $filtroEstado;
            }
            if ($filtroMateria && $filtroMateria != "all") {
                $condiciones["id_materia"] = $filtroMateria;
            }

            // Construir dinámicamente item y valor para mdlMostrarActividades
            $item = null;
            $valor = null;
            $sql_parts = [];
            foreach ($condiciones as $key => $val) {
                $sql_parts[] = "$key = :$key";
                $item = $key; // En este caso, solo pasamos el último item, pero el modelo debe manejarlo
                $valor = $val;
            }

            // Para simplificar, el modelo ya se unirá con materias, aquí solo pasamos los filtros de actividad
            // El modelo mdlMostrarActividades ha sido modificado para aceptar un $item y $valor
            // Si hay múltiples condiciones, el modelo necesitaría un array de condiciones.
            // Por ahora, asumiremos que se filtra principalmente por id_usuario en el modelo,
            // y luego se pueden aplicar filtros adicionales en la lógica de la vista o JS.
            // Para una consulta robusta con múltiples filtros, el modelo necesitaría una modificación mayor.
            // Aquí, estoy pasando id_usuario como el principal "item" y "valor" para el modelo.
            // Si necesitas filtros combinados directamente en el SQL del modelo, avísame.

            $respuesta = ModeloActividad::mdlMostrarActividades($tabla, "id_usuario", $id_usuario);
            
            // Filtrar en el controlador si hay filtros adicionales más allá del id_usuario
            if (($filtroEstado && $filtroEstado != "all") || ($filtroMateria && $filtroMateria != "all")) {
                $actividadesFiltradas = [];
                foreach ($respuesta as $actividad) {
                    $matchEstado = true;
                    $matchMateria = true;

                    if ($filtroEstado && $filtroEstado != "all") {
                        $matchEstado = ($actividad["estado"] == $filtroEstado);
                    }
                    if ($filtroMateria && $filtroMateria != "all") {
                        $matchMateria = ($actividad["id_materia"] == $filtroMateria);
                    }

                    if ($matchEstado && $matchMateria) {
                        $actividadesFiltradas[] = $actividad;
                    }
                }
                return $actividadesFiltradas;
            }
            return $respuesta;

        } else {
            return []; // Devolver un array vacío si no hay sesión
        }
    }

    /**
     * Edita una actividad existente.
     */
    public static function ctrEditarActividad() {
        if (isset($_POST["editarActividadObjetivo"])) {
            if (!empty($_POST["editarActividadObjetivo"]) && !empty($_POST["editarIdMateriaActividad"]) && !empty($_POST["editarActividadFecha"]) && isset($_POST["idActividadEditar"])) {
                if (isset($_SESSION["idUsuario"])) {
                    $tabla = "actividades";
                    $datos = array(
                        "id_actividad" => $_POST["idActividadEditar"],
                        "id_usuario" => $_SESSION["idUsuario"], // Para asegurar propiedad
                        "id_materia" => $_POST["editarIdMateriaActividad"],
                        "objetivo" => trim($_POST["editarActividadObjetivo"]),
                        "tipo" => $_POST["editarActividadTipo"] ?? 'Tarea',
                        "fecha_entrega" => $_POST["editarActividadFecha"],
                        "valor" => !empty($_POST["editarActividadValor"]) ? $_POST["editarActividadValor"] : null,
                        "estado" => $_POST["editarActividadEstado"] ?? 'Pendiente',
                        "nota" => !empty($_POST["editarActividadNota"]) ? $_POST["editarActividadNota"] : null
                    );

                    $respuesta = ModeloActividad::mdlEditarActividad($tabla, $datos);

                    if ($respuesta == "ok") {
                        echo '<script>
                                Swal.fire({
                                    icon: "success",
                                    title: "¡Actividad actualizada correctamente!",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function(){
                                    window.location = "index.php?ruta=actividades";
                                });
                              </script>';
                    } else {
                        echo '<script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Error al actualizar la actividad: ' . $respuesta . '",
                                    showConfirmButton: true
                                }).then(function(){
                                    window.location = "index.php?ruta=actividades";
                                });
                              </script>';
                    }
                } else {
                    echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Sesión no iniciada o datos insuficientes.",
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
                            title: "¡Los campos obligatorios de la actividad no pueden ir vacíos!",
                            showConfirmButton: true
                        }).then(function(){
                            window.location = "index.php?ruta=actividades";
                        });
                      </script>';
            }
        }
    }

    /**
     * Borra una actividad.
     */
    public static function ctrBorrarActividad() {
        if (isset($_GET["idActividad"])) {
            if (isset($_SESSION["idUsuario"])) {
                $tabla = "actividades";
                $datos = array(
                    "id_actividad" => $_GET["idActividad"],
                    "id_usuario" => $_SESSION["idUsuario"]
                );
                $respuesta = ModeloActividad::mdlBorrarActividad($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
                            Swal.fire({
                                icon: "success",
                                title: "¡Actividad borrada correctamente!",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function(){
                                window.location = "index.php?ruta=actividades";
                            });
                          </script>';
                } else {
                    echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Error al borrar la actividad: ' . $respuesta . '",
                                showConfirmButton: true
                            }).then(function(){
                                window.location = "index.php?ruta=actividades";
                            });
                          </script>';
                }
            } else {
                echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Sesión no iniciada. No se pudo borrar la actividad.",
                            showConfirmButton: true
                        }).then(function(){
                            window.location = "index.php?ruta=login";
                        });
                      </script>';
            }
        }
    }

    /**
     * Obtiene y devuelve el conteo de actividades por estado para el usuario actual.
     * @return array Array asociativo con los conteos (Pendiente, En Progreso, Completada, No Entregada).
     */
    public static function ctrContarActividadesPorEstado() {
        if (isset($_SESSION["idUsuario"])) {
            $id_usuario = $_SESSION["idUsuario"];
            $tabla = "actividades";
            return ModeloActividad::mdlContarActividadesPorEstado($tabla, $id_usuario);
        }
        return [
            'Pendiente' => 0,
            'En Progreso' => 0,
            'Completada' => 0,
            'No Entregada' => 0
        ];
    }

    /**
     * Obtiene y devuelve las 4 actividades "Pendiente" o "En Progreso" más próximas en fecha para el usuario actual.
     * @return array Array de objetos con los datos de las actividades.
     */
    public static function ctrObtenerProximasActividades() {
        if (isset($_SESSION["idUsuario"])) {
            $id_usuario = $_SESSION["idUsuario"];
            $tablaActividades = "actividades";
            $tablaMaterias = "materia";
            return ModeloActividad::mdlObtenerProximasActividades($tablaActividades, $tablaMaterias, $id_usuario);
        }
        return [];
    }
}