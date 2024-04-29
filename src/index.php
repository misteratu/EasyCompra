<?php
require_once __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/formSearch.php';

$tituloPagina = 'EasyChange';

$formSearch = new FormSearch();
$formHTML = $formSearch->generateForm(); // Genera el formulario HTML
$searchResults = $formSearch->processForm(); // Procesa los datos del formulario y genera el contenido de los resultados de la búsqueda

$contenidoPrincipal = <<<EOS
    $formHTML <!-- Muestra el formulario -->
    $searchResults <!-- Muestra los resultados de la búsqueda -->
EOS;

require __DIR__ . '/includes/vistas/plantilla.php';
