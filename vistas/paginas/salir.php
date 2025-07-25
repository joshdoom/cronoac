<?php
// vistas/paginas/salir.php

// Para poder manipular la sesión, primero debemos iniciarla.
session_start();

// session_unset() elimina todas las variables de la sesión actual.
// Borra $_SESSION["iniciarSesion"], $_SESSION["idUsuario"], etc.
session_unset();

// session_destroy() destruye toda la información asociada con la sesión actual.
// Es el paso final para limpiar todo.
session_destroy();

// Una vez que la sesión está destruida, redirigimos al usuario a la página de login.
// Usamos JavaScript para asegurar una redirección fluida.
echo '<script>
    window.location = "index.php?ruta=login";
</script>';

// Es una buena práctica detener la ejecución del script después de una redirección.
exit();