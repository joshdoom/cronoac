<?php
// modelos/conexion.php

/**
 * Clase Conexion
 *
 * Responsable de gestionar la conexión a la base de datos.
 * Utiliza el patrón Singleton para asegurar una única instancia de la conexión.
 */
class Conexion {

    /**
     * Método estático para conectar a la base de datos.
     *
     * @return PDO|false La instancia de la conexión PDO o false si falla.
     */
    static public function conectar() {

        // --- Información de la base de datos ---
        // Nombre del host donde está la base de datos. 'localhost' es común.
        $host = "localhost";
        // Nombre de la base de datos a la que nos queremos conectar.
        $dbname = "cronoac1";
        // Usuario de la base de datos. 'root' es el usuario por defecto en XAMPP.
        $usuario = "root";
        // Contraseña del usuario. Por defecto en XAMPP, la contraseña está vacía.
        $contraseña = "";

        try {
            // Creamos una nueva instancia de PDO (PHP Data Objects).
            // Esta es la forma moderna y segura de conectar PHP con una base de datos.
            // La cadena de conexión (DSN) especifica el tipo de base de datos, el host y el nombre de la DB.
            $link = new PDO(
                "mysql:host=" . $host . ";dbname=" . $dbname,
                $usuario,
                $contraseña
            );

            // Configuramos el PDO para que use el juego de caracteres UTF-8.
            // Esto es muy importante para evitar problemas con tildes y caracteres especiales.
            $link->exec("set names utf8");
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Si la conexión es exitosa, devolvemos el objeto de conexión.
            return $link;

        } catch (PDOException $e) {
            
            // Si algo sale mal durante la conexión, capturamos la excepción.
            // Mostramos un mensaje de error para saber qué pasó.
            // En un entorno de producción, este error debería guardarse en un archivo de log
            // en lugar de mostrarse directamente al usuario.
            die("Error al conectar con la base de datos: " . $e->getMessage());

        }
    }
}
