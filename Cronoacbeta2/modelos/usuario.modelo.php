<?php
// modelos/usuario.modelo.php

require_once "conexion.php";

/**
 * Clase ModeloUsuario
 * Contiene los métodos para interactuar con las tablas de usuarios.
 */
class ModeloUsuario {

    /**
     * Muestra un usuario específico de la base de datos.
     */
    public static function mdlMostrarUsuarios($tabla, $item, $valor) {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
        $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Crea un nuevo usuario en la base de datos (VERSIÓN FINAL CORREGIDA).
     *
     * @return string Devuelve "ok" si es exitoso, o el mensaje de error si falla.
     */
    public static function mdlCrearUsuario($tablaUsuario, $datosUsuario, $tablaDatosUsuario, $datosPersonales) {
        
        $link = Conexion::conectar();
        $link->beginTransaction();

        try {
            // --- 1. Insertar en la tabla 'usuario' ---
            $stmtUsuario = $link->prepare("INSERT INTO $tablaUsuario (correo, password) VALUES (:correo, :password)");

            // Pasamos los datos directamente al execute. Es más limpio y seguro.
            $stmtUsuario->execute([
                ":correo" => $datosUsuario["correo"],
                ":password" => $datosUsuario["password"]
            ]);

            $idUsuario = $link->lastInsertId();

            // --- 2. Insertar en la tabla 'datos_de_usuario' ---
            $stmtDatos = $link->prepare(
                "INSERT INTO $tablaDatosUsuario (id_usuario, nombre, segundo_nombre, apellido, segundo_apellido, cedula_identidad) 
                 VALUES (:id_usuario, :nombre, :segundo_nombre, :apellido, :segundo_apellido, :cedula_identidad)"
            );
            
            // Preparamos y pasamos el array de datos directamente al execute.
            // Si un campo opcional está vacío, se guardará como NULL en la base de datos.
            $stmtDatos->execute([
                ":id_usuario" => $idUsuario,
                ":nombre" => $datosPersonales["nombre"],
                ":segundo_nombre" => !empty($datosPersonales["segundo_nombre"]) ? $datosPersonales["segundo_nombre"] : null,
                ":apellido" => $datosPersonales["apellido"],
                ":segundo_apellido" => !empty($datosPersonales["segundo_apellido"]) ? $datosPersonales["segundo_apellido"] : null,
                ":cedula_identidad" => !empty($datosPersonales["cedula_identidad"]) ? $datosPersonales["cedula_identidad"] : null
            ]);

            $link->commit();
            return "ok";

        } catch (Exception $e) {
            $link->rollBack();
            return "Error al crear usuario: " . $e->getMessage();
        }
    }
}

