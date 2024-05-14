<?php
require_once __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/formPerfil.php';

$tituloPagina = 'EasyCompra';

$formPerfil = new FormPerfil();
$formHTML = $formPerfil->generateForm(); // Genera el formulario HTML
$formPerfilHTML = $formPerfil->processForm();

if(isset($_SESSION["login"])){
    $contenidoPrincipal = <<<EOS
    $formHTML <!-- Muestra el formulario -->
    $formPerfilHTML
    EOS;
}else{
    $contenidoPrincipal = <<<EOS
    <h1>Usuario no logeado</h1>
    <h3>Por favor inicie sesion o registrese para poder subir productos<h3>
    EOS;
}

require __DIR__ . '/includes/vistas/plantilla.php';
