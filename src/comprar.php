<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/formComprar.php';

$tituloPagina = 'Comprar producto';

$formComprar = new FormComprar();
$formHTML = $formComprar->generateForm(); // Genera el formulario HTML
$loginResults = $formComprar->processForm(); // Procesa los datos del formulario y genera el contenido de los resultados de la búsqueda

if(isset($_SESSION["login"])){
    $contenidoPrincipal = <<<EOS
        $formHTML <!-- Muestra el formulario -->
        $loginResults <!-- Muestra los resultados del formulario -->
    EOS;
}else{
    $contenidoPrincipal = <<<EOS
    <h1>Usuario no logeado</h1>
    <h3>Por favor inicie sesion o registrese para poder comprar productos<h3>
    EOS;
}

require_once __DIR__ . '/includes/vistas/plantilla.php';
