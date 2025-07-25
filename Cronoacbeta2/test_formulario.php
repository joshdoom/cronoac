<?php
    // test_formulario.php

    // Primero, verificamos si se ha enviado algo por POST.
    if (!empty($_POST)) {
        // Si hay datos, los mostramos.
        echo "<h1>¡Éxito! Se recibieron los datos por POST:</h1>";
        echo "<pre>"; // La etiqueta <pre> formatea el texto para que sea más legible.
        var_dump($_POST);
        echo "</pre>";
        
        echo '<hr><a href="test_formulario.php">Volver a intentar</a>';

    } else {
        // Si no hay datos POST, mostramos el formulario.
        echo "<h1>Prueba de Envío de Formulario</h1>";
        echo "<p>Escribe algo en el campo y haz clic en Enviar. Si el servidor funciona, deberías ver los datos en la siguiente pantalla.</p>";
        
        // Este es un formulario HTML muy simple que se envía a sí mismo.
        echo '
        <form action="test_formulario.php" method="post">
            <label for="dato_prueba">Dato de Prueba:</label>
            <input type="text" id="dato_prueba" name="campo_de_prueba" required>
            <br><br>
            <button type="submit">Enviar</button>
        </form>
        ';
    }
?>
