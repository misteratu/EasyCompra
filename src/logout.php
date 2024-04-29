<?php
require_once __DIR__ . '/includes/config.php';

//Doble seguridad: unset + destroy
unset($_SESSION["login"]);
unset($_SESSION["esAdmin"]);
unset($_SESSION["nombre"]);
unset($_SESSION["email"]);


session_destroy();

require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'EasyChange';

$contenidoPrincipal = <<<EOS
<h1>Hasta pronto!</h1>
EOS;

require __DIR__ . '/includes/vistas/plantilla.php';