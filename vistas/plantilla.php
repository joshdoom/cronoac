<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- El título de la página podría ser dinámico en el futuro, pero por ahora lo dejamos fijo. -->
    <title>CronoAC - Gestor Académico</title>

    <!-- Enlaces a recursos externos (CDN) para estilos y librerías. -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="vistas/styles/login.css" rel="stylesheet">

    <!-- Estilos base para la aplicación. -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f7f4;
        }
        .nav-link.active {
            background-color: #e0e7ff;
            color: #3730a3;
            font-weight: 600;
        }
    </style>
</head>

<body>

<?php
// =================================================================
// ESTRUCTURA PRINCIPAL DE LA APLICACIÓN
// Aquí decidimos qué mostrarle al usuario.
// =================================================================

// Primero, verificamos si la variable de sesión "iniciarSesion" existe y si su valor es "ok".
// Esta variable la crearemos en el controlador de usuarios cuando el login sea exitoso.
if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {

    // --- EL USUARIO HA INICIADO SESIÓN ---
    // Si la sesión es válida, mostramos la estructura completa del panel de control.
    
    echo '<div class="flex h-screen antialiased">';

    // 1. Incluimos el menú lateral (sidebar).
    // Para simplificar, el HTML del menú está aquí mismo, pero podría estar en un archivo aparte.
    echo '
    <aside class="w-64 bg-white shadow-md flex flex-col flex-shrink-0">
        <div class="h-20 flex items-center justify-center border-b">
            <h1 class="text-2xl font-bold text-indigo-800">CronoAC</h1>
        </div>
        <nav class="flex-grow px-4 py-6">
            <a href="index.php?ruta=dashboard" class="nav-link flex items-center px-4 py-3 my-1 rounded-lg text-gray-700 hover:bg-indigo-50">
                <span class="mr-3">📊</span> Panel Principal
            </a>
            <a href="index.php?ruta=actividades" class="nav-link flex items-center px-4 py-3 my-1 rounded-lg text-gray-700 hover:bg-indigo-50">
                <span class="mr-3">📝</span> Mis Actividades
            </a>
            <a href="index.php?ruta=materias" class="nav-link flex items-center px-4 py-3 my-1 rounded-lg text-gray-700 hover:bg-indigo-50">
                <span class="mr-3">📚</span> Materias
            </a>
            <a href="index.php?ruta=miembros" class="nav-link flex items-center px-4 py-3 my-1 rounded-lg text-gray-700 hover:bg-indigo-50">
                <span class="mr-3">👥</span> Miembros
            </a>
            <a href="index.php?ruta=salir" class="nav-link flex items-center px-4 py-3 my-1 rounded-lg text-gray-700 hover:bg-indigo-50">
                <span class="mr-3">👋</span> Salir
            </a>
        </nav>
        <div class="p-4 border-t">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-indigo-200 flex items-center justify-center font-bold text-indigo-700">
                    AC
                </div>
                <div class="ml-3">
                    <!-- Estos datos vendrán de la base de datos más adelante -->
                    <p class="text-sm font-semibold">' . $_SESSION["nombre"] . '</p>
                    <p class="text-xs text-gray-500">' . $_SESSION["correo"] . '</p>
                </div>
            </div>
        </div>
    </aside>';

    // 2. Definimos el área principal donde se cargará el contenido de cada página.
    echo '<main class="flex-1 overflow-y-auto p-6 md:p-10">';

    // --- GESTOR DE RUTAS (ROUTER) ---
    // Verificamos si se ha pasado una "ruta" por la URL (ej: index.php?ruta=dashboard)
    if (isset($_GET["ruta"])) {
        // Lista blanca de rutas permitidas para usuarios con sesión iniciada.
        $rutasPermitidas = ["dashboard", "actividades", "materias", "miembros", "salir"];

        if (in_array($_GET["ruta"], $rutasPermitidas)) {
            // Si la ruta está en nuestra lista, incluimos el archivo correspondiente.
            include "paginas/" . $_GET["ruta"] . ".php";
        } else {
            // Si la ruta no es válida, mostramos una página de error 404.
            include "paginas/404.php";
        }
    } else {
        // Si no se especifica ninguna ruta en la URL, cargamos el dashboard por defecto.
        include "paginas/dashboard.php";
    }

    echo '</main>'; // Cierre del área de contenido principal
    echo '</div>'; // Cierre del contenedor flex principal


} else {

    // --- EL USUARIO NO HA INICIADO SESIÓN ---
    // Si no hay una sesión activa, simplemente incluimos la página de login.
    // Esto previene que usuarios no autenticados vean el panel de control.
    include "paginas/login.php";

}

?>

<!-- Scripts de JavaScript. Es buena práctica cargarlos al final del body. -->
<!-- Aquí irían los enlaces a tus archivos .js personalizados en el futuro. -->
<!-- <script src="vistas/js/main.js"></script> -->

</body>
</html>
