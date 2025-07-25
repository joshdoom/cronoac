<?php
// Este archivo es parte del proyecto CronoAC.
// Aquí se muestra la información de los desarrolladores del proyecto.
?>

<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-xl shadow-lg">

        <!-- Formulario principal -->
        <div id="miembros-form-container">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-indigo-800">CronoAC</h1>
                <h2 class="mt-2 text-2xl font-semibold text-gray-900">Informacion de desarrolladores</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Desarrolladores
                    <div>
                        <button id="show-alejandro-data-btn" class="font-medium text-indigo-600 hover:text-indigo-500">Alejandro</button>
                        <br>
                        <button id="show-joshua-data-btn" class="font-medium text-indigo-600 hover:text-indigo-500">Joshua</button>
                        <br>
                        <button id="show-jhon-data-btn" class="font-medium text-indigo-600 hover:text-indigo-500">Jhon</button>
                        <br>
                        <button id="show-enmanuel-data-btn" class="font-medium text-indigo-600 hover:text-indigo-500">Enmanuel</button>
                    </div>
                </p>
            </div>
        </div>

        <!-- Información de Alejandro -->
        <div id="developer-alejandro-data" class="hidden mt-4 text-sm text-gray-600">
            <p><strong>Nombre completo:</strong> Alejandro David Carbonell Aular.</p>
            <p><strong>Cedula de identidad:</strong> 31.030.096.</p>
            <p><strong>Correo electrónico:</strong> aularalejandro8@gmail.com</p>
            <p><strong>GitHub:</strong> <a href="https://github.com/Khaaslot" target="_blank">Khaaslot</a></p>
            <p><strong>Descripción:</strong> Desarrollador Backend y Frontend, mis pasatiempos son los videojuegos y la música.</p>
            <button class="mt-4 font-medium text-indigo-600 hover:text-indigo-500" id="back-btn-alejandro">Volver</button>
        </div>

        <!-- Información de Joshua -->
        <div id="developer-joshua-data" class="hidden mt-4 text-sm text-gray-600">
            <p><strong>Nombre completo:</strong> Joshua Moises Vizcaino Canales.</p>
            <p><strong>Cedula de identidad:</strong> 28.623.901.</p>
            <p><strong>Correo electrónico:</strong> joshua.vizcaino30@gmail.com</p>
            <p><strong>Instagram:</strong> <a href="https://www.instagram.com/joshdoom__/" target="_blank">@joshdoom__</a></p>
            <p><strong>Descripción:</strong> Estudiante de informatica, amante del arte y los videojuegos, dibujo como hobbie, mis comidas favoritas son las papas y la sopa :p.</p>
            <button class="mt-4 font-medium text-indigo-600 hover:text-indigo-500" id="back-btn-joshua">Volver</button>
        </div>

        <!-- Información de Jhon -->
        <div id="developer-jhon-data" class="hidden mt-4 text-sm text-gray-600">
            <p><strong>Nombre completo:</strong> Jhon Kelvin Meneses Bastardo.</p>
            <p><strong>Cedula de identidad:</strong> 30.933.306.</p>
            <p><strong>Correo electrónico:</strong> menesesjk06@gmail.com</p>
            <p><strong>Instagram:</strong> <a href="https://instagram.com/menesesjk06" target="_blank">@menesesjk06</a></p>
            <p><strong>Descripción:</strong> Estudiante, Me encanta dibujar, soy Fan de Formula 1, Escucho música como el Phonk, me gusta One Piece.</p>
            <button class="mt-4 font-medium text-indigo-600 hover:text-indigo-500" id="back-btn-jhon">Volver</button>
        </div>
        <!-- Información de Enmanuel -->
        <div id="developer-enmanuel-data" class="hidden mt-4 text-sm text-gray-600">
            <p><strong>Nombre completo:</strong>  Enmanuel De Jesús Alvarado García .</p>
            <p><strong>Cedula de identidad:</strong> 28.692.479</p>
            <p><strong>Correo electrónico:</strong> e.a.u.e2411@gmail.com</p>
            <p><strong>GitHub:</strong> <a href="">Enmanuel</a></p>
            <p><strong>Descripción:</strong> principiante en desarrollo Backend y Frontend, amante de los Gatos y la música Electrónica.</p>
            <button class="mt-4 font-medium text-indigo-600 hover:text-indigo-500" id="back-btn-enmanuel">Volver</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const miembrosContainer = document.getElementById('miembros-form-container');
        const alejandroData = document.getElementById('developer-alejandro-data');
        const joshuaData = document.getElementById('developer-joshua-data');
        const jhonData = document.getElementById('developer-jhon-data');

        document.getElementById('show-alejandro-data-btn').addEventListener('click', () => {
            miembrosContainer.classList.add('hidden');
            alejandroData.classList.remove('hidden');
        });

        document.getElementById('show-joshua-data-btn').addEventListener('click', () => {
            miembrosContainer.classList.add('hidden');
            joshuaData.classList.remove('hidden');
        });

        document.getElementById('show-jhon-data-btn').addEventListener('click', () => {
            miembrosContainer.classList.add('hidden');
            jhonData.classList.remove('hidden');
        });
        document.getElementById('show-enmanuel-data-btn').addEventListener('click', () => {
            miembrosContainer.classList.add('hidden');
            document.getElementById('developer-enmanuel-data').classList.remove('hidden');
        });

        document.getElementById('back-btn-alejandro').addEventListener('click', () => {
            alejandroData.classList.add('hidden');
            miembrosContainer.classList.remove('hidden');
        });

        document.getElementById('back-btn-joshua').addEventListener('click', () => {
            joshuaData.classList.add('hidden');
            miembrosContainer.classList.remove('hidden');
        });

        document.getElementById('back-btn-jhon').addEventListener('click', () => {
            jhonData.classList.add('hidden');
            miembrosContainer.classList.remove('hidden');
        });
        document.getElementById('developer-enmanuel-data').addEventListener('click', () => {
            document.getElementById('developer-enmanuel-data').classList.add('hidden');
            miembrosContainer.classList.remove('hidden');
        });
    });
</script>