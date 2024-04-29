<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/formLogin.php';

$tituloPagina = 'Login';

$formLogin = new FormLogin();
$formLogin->processForm(); // Primero procesamos el fomulario, en caso de no habe un POST no se va a procesar nada y pasa a la siguiente instruccion sin generar nada
$formHTML = $formLogin->generateForm(); // Genera el formulario HTML

$contenidoPrincipal = <<<EOS
    $formHTML <!-- Muestra el formulario -->
EOS;

require_once __DIR__ . '/includes/vistas/plantilla.php';
