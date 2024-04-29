<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/formEliminarProducto.php'; // Importa la clase FormEliminarProducto

$formEliminarProducto = new FormEliminarProducto();
$formEliminarProductoHTML = $formEliminarProducto->generateForm();
$eliminationResults = $formEliminarProducto->processForm(); 

$contenidoPrincipal = <<<EOS
<h1>Eliminar Producto</h1>

<div class="productos">
    $formEliminarProductoHTML
    $eliminationResults
</div>
EOS;

require_once __DIR__ . '/includes/vistas/plantilla.php';
?>