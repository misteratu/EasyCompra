<?php
require_once __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/formAdmin.php';

$tituloPagina = 'EasyChange';

$formAdmin = new FormAdmin();
$formHTML = $formAdmin->generateForm(); // Genera el formulario HTML
$formAdminHTML = $formAdmin->processForm();

if(isset($_SESSION["login"])){
    if(isset($_SESSION["esAdmin"]) && $_SESSION["esAdmin"] == 1){
        $contenidoPrincipal = <<<EOS
        $formHTML <!-- Muestra el formulario -->
        $formAdminHTML
        EOS;
    }else{
        $contenidoPrincipal = <<<EOS
        <h1>Usuario no autorizado</h1>
        <h3>Por favor inicie sesion como administrador para poder acceder a esta pagina<h3>
        EOS;
    }
    
}else{
    $contenidoPrincipal = <<<EOS
    <h1>Usuario no logeado</h1>
    <h3>Por favor inicie sesion o registrese para poder subir productos<h3>
    EOS;
}

require __DIR__ . '/includes/vistas/plantilla.php';
