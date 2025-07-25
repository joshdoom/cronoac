<?php
// modelos/materia.modelo.php

require_once "conexion.php";

class ModeloMateria {

    /**
     * Crea una nueva materia en la base de datos.
     * @param string $tabla Nombre de la tabla (materia).
     * @param array $datos Array asociativo con id_usuario y nombre_materia.
     * @return string "ok" si es exitoso, o un mensaje de error.
     */
    public static function mdlCrearMateria($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_usuario, nombre_materia) VALUES (:id_usuario, :nombre_materia)");
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":nombre_materia", $datos["nombre_materia"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "Error al crear la materia: " . $stmt->errorInfo()[2];
        }
    }

    /**
     * Muestra las materias de la base de datos.
     * @param string $tabla Nombre de la tabla (materia).
     * @param string|null $item Columna a buscar (ej. "id_usuario", "id_materia").
     * @param mixed|null $valor Valor a buscar.
     * @return array|object|false Un array de materias, una materia individual, o false si no se encuentra.
     */
    public static function mdlMostrarMaterias($tabla, $item = null, $valor = null) {
        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(); // Puede devolver múltiples materias para un usuario
        } else {
            // Si no se especifica item/valor, podría mostrar todas (usar con precaución en prod)
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

    /**
     * Edita una materia existente.
     * @param string $tabla Nombre de la tabla (materia).
     * @param array $datos Array asociativo con id_materia y nuevo_nombre_materia.
     * @return string "ok" si es exitoso, o un mensaje de error.
     */
    public static function mdlEditarMateria($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre_materia = :nombre_materia WHERE id_materia = :id_materia AND id_usuario = :id_usuario");
        $stmt->bindParam(":nombre_materia", $datos["nombre_materia"], PDO::PARAM_STR);
        $stmt->bindParam(":id_materia", $datos["id_materia"], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT); // Asegurar que el usuario solo edite sus propias materias

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "Error al editar la materia: " . $stmt->errorInfo()[2];
        }
    }

    /**
     * Borra una materia de la base de datos.
     * @param string $tabla Nombre de la tabla (materia).
     * @param array $datos Array asociativo con id_materia y id_usuario.
     * @return string "ok" si es exitoso, o un mensaje de error.
     */
    public static function mdlBorrarMateria($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_materia = :id_materia AND id_usuario = :id_usuario");
        $stmt->bindParam(":id_materia", $datos["id_materia"], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT); // Asegurar que el usuario solo borre sus propias materias

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "Error al borrar la materia: " . $stmt->errorInfo()[2];
        }
    }
}