<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/formMostrar.php';

$tituloPagina = 'Mostrar Producto';

$formMostrar = new FormMostrar();
$htmlContent = $formMostrar->processForm();

$contenidoPrincipal = <<<EOS
    $htmlContent <!-- Muestra el contenido del producto -->
EOS;

require_once __DIR__ . '/includes/vistas/plantilla.php';
?>
