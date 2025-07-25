<?php
// vistas/paginas/login.php
// Esta es la vista que se muestra cuando el usuario no ha iniciado sesión.
// Es incluida por plantilla.php.
?>

<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">

    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-xl shadow-lg">

        <!-- ====================================================== -->
        <!-- Formulario de Inicio de Sesión (Visible por defecto) -->
        <!-- ====================================================== -->
        <div id="login-form-container">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-indigo-800">CronoAC</h1>
                <h2 class="mt-2 text-2xl font-semibold text-gray-900">Inicia Sesión</h2>
                <p class="mt-2 text-sm text-gray-600">
                    ¿No tienes una cuenta?
                    <button id="show-register-btn" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Regístrate aquí
                    </button>
                </p>
            </div>

            <!-- El formulario usa el método POST. La acción se deja en blanco para que 
                 los datos se envíen a la misma URL (nuestro index.php). -->
            <form class="mt-8 space-y-6" method="post">
                <div class="space-y-4 rounded-md shadow-sm">
                    <div>
                        <label for="login-correo" class="sr-only">Correo electrónico</label>
                        <input id="login-correo" name="ingresoCorreo" type="email" autocomplete="email" required class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Correo electrónico">
                    </div>
                    <div>
                        <label for="login-contraseña" class="sr-only">Contraseña</label>
                        <input id="login-contraseña" name="ingresoPassword" type="password" autocomplete="current-password" required class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contraseña">
                    </div>
                </div>

                <div>
                    <button type="submit" class="relative flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md group hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Ingresar
                    </button>
                </div>
            </form>
        </div>

        <!-- ====================================================== -->
        <!-- Formulario de Registro (Oculto por defecto)          -->
        <!-- ====================================================== -->
       <div id="register-form-container" class="hidden">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-indigo-800">CronoAC</h1>
                <h2 class="mt-2 text-2xl font-semibold text-gray-900">Crea tu Cuenta</h2>
                <p class="mt-2 text-sm text-gray-600">
                    ¿Ya tienes una cuenta?
                    <button id="show-login-btn" class="font-medium text-indigo-600 hover:text-indigo-500">Inicia sesión</button>
                </p>
            </div>

            <form class="mt-8 space-y-4" method="post">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="register-nombre" class="sr-only">Nombre</label>
                        <input id="register-nombre" name="registroNombre" type="text" required class="block w-full px-3 py-2 placeholder-gray-500 border rounded-md" placeholder="Primer Nombre">
                    </div>
                    <div>
                        <!-- CAMPO NUEVO -->
                        <label for="register-segundo-nombre" class="sr-only">Segundo Nombre</label>
                        <input id="register-segundo-nombre" name="registroSegundoNombre" type="text" class="block w-full px-3 py-2 placeholder-gray-500 border rounded-md" placeholder="Segundo Nombre (Opcional)">
                    </div>
                     <div>
                        <label for="register-apellido" class="sr-only">Apellido</label>
                        <input id="register-apellido" name="registroApellido" type="text" required class="block w-full px-3 py-2 placeholder-gray-500 border rounded-md" placeholder="Primer Apellido">
                    </div>
                    <div>
                        <!-- CAMPO NUEVO -->
                        <label for="register-segundo-apellido" class="sr-only">Segundo Apellido</label>
                        <input id="register-segundo-apellido" name="registroSegundoApellido" type="text" class="block w-full px-3 py-2 placeholder-gray-500 border rounded-md" placeholder="Segundo Apellido (Opcional)">
                    </div>
                </div>
                 <div>
                    <label for="register-cedula" class="sr-only">Cédula</label>
                    <input id="register-cedula" name="registroCedula" type="text" placeholder="Cédula (Opcional)" class="block w-full px-3 py-2 placeholder-gray-500 border rounded-md">
                </div>
                <div>
                    <label for="register-correo" class="sr-only">Correo</label>
                    <input id="register-correo" name="registroCorreo" type="email" required class="block w-full px-3 py-2 placeholder-gray-500 border rounded-md" placeholder="Correo Electrónico">
                </div>
                <div>
                    <label for="register-contraseña" class="sr-only">Contraseña</label>
                    <input id="register-contraseña" name="registroPassword" type="password" required class="block w-full px-3 py-2 placeholder-gray-500 border rounded-md" placeholder="Contraseña">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Crear Cuenta</button>
                </div>
            </form>
        </div>

    </div>
</div>

<?php
    // --- PROCESAMIENTO DEL FORMULARIO ---
    // Este bloque de PHP se ejecuta después de que el HTML ha sido renderizado.
    // Su trabajo es llamar al controlador para que maneje los datos enviados por el usuario.

    // Creamos una instancia del controlador de usuarios.
    // NOTA: Para que esto funcione, debemos haber incluido el archivo del controlador
    // en nuestro index.php principal.
    $registro = new ControladorUsuario();

    // Llamamos al método que se encarga de procesar el registro.
    // Este método, por dentro, verificará si se enviaron los datos del formulario de registro.
    $registro -> ctrCrearUsuario();

    // Llamamos al método para procesar el inicio de sesión.
    $login = new ControladorUsuario();
    $login -> ctrIngresoUsuario();

?>

<!-- Script para alternar entre los formularios -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginContainer = document.getElementById('login-form-container');
        const registerContainer = document.getElementById('register-form-container');
        
        const showRegisterBtn = document.getElementById('show-register-btn');
        const showLoginBtn = document.getElementById('show-login-btn');

        showRegisterBtn.addEventListener('click', () => {
            loginContainer.classList.add('hidden');
            registerContainer.classList.remove('hidden');
        });

        showLoginBtn.addEventListener('click', () => {
            registerContainer.classList.add('hidden');
            loginContainer.classList.remove('hidden');
        });
    });
</script>
