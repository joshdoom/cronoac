<?php
// index.php (Controlador Frontal v2)
// Este es el único punto de entrada a la aplicación.
  
// --- 1. Iniciar la sesión ---
// Esto debe estar al principio de todo para que $_SESSION esté disponible en todas partes.
session_start();


// --- 2. Cargar los Modelos y Controladores ---
// Es crucial que las clases existan ANTES de intentar usarlas.
require_once "controladores/usuario.controlador.php";
require_once "modelos/conexion.php";
require_once "modelos/usuario.modelo.php";
require_once "modelos/materias.modelo.php"; // <--- Asegúrate de que esta línea esté presente
require_once "modelos/actividades.modelo.php";
require_once "controladores/materias.controlador.php"; 
require_once "controladores/actividades.controlador.php"; 

// (Aquí se cargarían los demás controladores y modelos del proyecto)


// --- 3. Procesar las Peticiones del Usuario (Acciones) ---
// Antes de mostrar cualquier HTML, verificamos si el usuario está intentando
// realizar una acción, como iniciar sesión o registrarse.
$controladorUsuario = new ControladorUsuario();
$controladorUsuario->ctrIngresoUsuario();
$controladorUsuario->ctrCrearUsuario();


// --- 4. Cargar la Plantilla Principal ---
// Una vez que cualquier acción ha sido procesada, cargamos la plantilla.
// La plantilla se encargará de mostrar la vista correcta (login o dashboard)
// basándose en si la sesión se inició correctamente en el paso anterior.
    require_once "vistas/plantilla.php";
