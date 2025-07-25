<?php
// modelos/actividad.modelo.php

require_once "conexion.php";

class ModeloActividad {

    /**
     * Crea una nueva actividad en la base de datos.
     * @param string $tabla Nombre de la tabla (actividades).
     * @param array $datos Array asociativo con los datos de la actividad.
     * @return string "ok" si es exitoso, o un mensaje de error.
     */
    public static function mdlCrearActividad($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_usuario, id_materia, objetivo, tipo, fecha_entrega, valor, estado, nota) VALUES (:id_usuario, :id_materia, :objetivo, :tipo, :fecha_entrega, :valor, :estado, :nota)");

        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt->bindParam(":id_materia", $datos["id_materia"], PDO::PARAM_INT);
        $stmt->bindParam(":objetivo", $datos["objetivo"], PDO::PARAM_STR);
        $stmt->bindParam(":tipo", $datos["tipo"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_entrega", $datos["fecha_entrega"], PDO::PARAM_STR); // DATE type
        $stmt->bindParam(":valor", $datos["valor"], PDO::PARAM_STR); // DECIMAL type, bind as STR
        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
        $stmt->bindParam(":nota", $datos["nota"], PDO::PARAM_STR); // DECIMAL type, bind as STR

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "Error al crear la actividad: " . $stmt->errorInfo()[2];
        }
    }

    /**
     * Muestra las actividades de la base de datos.
     * @param string $tabla Nombre de la tabla (actividades).
     * @param string|null $item Columna a buscar (ej. "id_usuario", "id_materia", "estado").
     * @param mixed|null $valor Valor a buscar.
     * @param string|null $orden Columna para ordenar resultados.
     * @return array Un array de actividades.
     */
    public static function mdlMostrarActividades($tabla, $item = null, $valor = null, $orden = "fecha_entrega") {
        $sql = "SELECT a.*, m.nombre_materia FROM $tabla a JOIN materia m ON a.id_materia = m.id_materia";
        $params = [];

        if ($item != null) {
            $sql .= " WHERE a.$item = :$item";
            $params[":".$item] = $valor;
        }

        $sql .= " ORDER BY $orden ASC"; // Ordena por fecha de entrega por defecto

        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Edita una actividad existente.
     * @param string $tabla Nombre de la tabla (actividades).
     * @param array $datos Array asociativo con los datos actualizados de la actividad.
     * @return string "ok" si es exitoso, o un mensaje de error.
     */
    public static function mdlEditarActividad($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_materia = :id_materia, objetivo = :objetivo, tipo = :tipo, fecha_entrega = :fecha_entrega, valor = :valor, estado = :estado, nota = :nota WHERE id_actividad = :id_actividad AND id_usuario = :id_usuario");

        $stmt->bindParam(":id_materia", $datos["id_materia"], PDO::PARAM_INT);
        $stmt->bindParam(":objetivo", $datos["objetivo"], PDO::PARAM_STR);
        $stmt->bindParam(":tipo", $datos["tipo"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_entrega", $datos["fecha_entrega"], PDO::PARAM_STR);
        $stmt->bindParam(":valor", $datos["valor"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
        $stmt->bindParam(":nota", $datos["nota"], PDO::PARAM_STR);
        $stmt->bindParam(":id_actividad", $datos["id_actividad"], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT); // Asegurar que el usuario solo edite sus propias actividades

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "Error al editar la actividad: " . $stmt->errorInfo()[2];
        }
    }

    /**
     * Borra una actividad de la base de datos.
     * @param string $tabla Nombre de la tabla (actividades).
     * @param array $datos Array asociativo con id_actividad y id_usuario.
     * @return string "ok" si es exitoso, o un mensaje de error.
     */
    public static function mdlBorrarActividad($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_actividad = :id_actividad AND id_usuario = :id_usuario");
        $stmt->bindParam(":id_actividad", $datos["id_actividad"], PDO::PARAM_INT);
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT); // Asegurar que el usuario solo borre sus propias actividades

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "Error al borrar la actividad: " . $stmt->errorInfo()[2];
        }
    }

    /**
     * Obtiene el conteo de actividades por estado para un usuario específico.
     * @param string $tabla Nombre de la tabla (actividades).
     * @param int $id_usuario ID del usuario.
     * @return array Un array asociativo con el estado como clave y el conteo como valor.
     */
    public static function mdlContarActividadesPorEstado($tabla, $id_usuario) {
        $stmt = Conexion::conectar()->prepare("SELECT estado, COUNT(*) as count FROM $tabla WHERE id_usuario = :id_usuario GROUP BY estado");
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $conteoEstados = [
            'Pendiente' => 0,
            'En Progreso' => 0,
            'Completada' => 0,
            'No Entregada' => 0
        ];

        foreach ($resultados as $row) {
            $conteoEstados[$row['estado']] = $row['count'];
        }

        return $conteoEstados;
    }

    /**
     * Obtiene las 4 actividades "Pendiente" o "En Progreso" más próximas en fecha para un usuario específico.
     * @param string $tablaActividades Nombre de la tabla de actividades.
     * @param string $tablaMaterias Nombre de la tabla de materias.
     * @param int $id_usuario ID del usuario.
     * @return array Un array de arrays asociativos con los datos de las actividades.
     */
    public static function mdlObtenerProximasActividades($tablaActividades, $tablaMaterias, $id_usuario) {
        $stmt = Conexion::conectar()->prepare("
            SELECT a.objetivo, a.fecha_entrega, a.estado, m.nombre_materia
            FROM $tablaActividades a
            JOIN $tablaMaterias m ON a.id_materia = m.id_materia
            WHERE a.id_usuario = :id_usuario
            AND (a.estado = 'Pendiente' OR a.estado = 'En Progreso')
            ORDER BY a.fecha_entrega ASC
            LIMIT 4
        ");
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}