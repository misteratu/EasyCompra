<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/formMisProductos.php';


$tituloPagina = 'Mis Productos';

$formMisProductos = new formMisProductos();
$formHTML = $formMisProductos->generateForm(); // Genera el formulario HTML
$searchResults = $formMisProductos->processForm(); // Procesa los datos del formulario y genera el contenido de los resultados de la b�squeda

if(isset($_SESSION["login"])){
  $contenidoPrincipal = <<<EOS
    $formHTML <!-- Muestra el formulario -->
    $searchResults
    EOS;
}else{
    $contenidoPrincipal = <<<EOS
    <h1>Usuario no logeado</h1>
    <h3>Por favor inicie sesion o registrese para poder ver sus productos<h3>
    EOS;
}

require_once __DIR__ . '/includes/vistas/plantilla.php';
?>
