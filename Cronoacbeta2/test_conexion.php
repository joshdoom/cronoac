<?php
// test_conexion.php

echo "<h1>Prueba de Conexión a la Base de Datos</h1>";

// 1. Incluimos nuestro archivo de conexión.
// Si este 'require_once' falla, el problema es la ruta al archivo.
require_once "modelos/conexion.php";

echo "<p>Intentando conectar...</p>";

// 2. Llamamos al método para conectar.
$conexion = Conexion::conectar();

// 3. Verificamos el resultado.
if ($conexion) {
    echo "<p style='color: green; font-weight: bold;'>¡Conexión exitosa a la base de datos!</p>";
    echo "<p>La configuración en 'conexion.php' es correcta.</p>";
    // Cerramos la conexión para ser limpios.
    $conexion = null;
} else {
    echo "<p style='color: red; font-weight: bold;'>Error: No se pudo establecer la conexión.</p>";
    echo "<p>Revisa las credenciales (host, dbname, usuario, contraseña) en el archivo 'modelos/conexion.php'.</p>";
}

?>