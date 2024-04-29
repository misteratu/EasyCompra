<?php
function mostrarSaludo() {
	$rutaApp = RUTA_APP;
    
	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
		return "Bienvenido, {$_SESSION['nombre']} <a href='{$rutaApp}/logout.php'>(salir)</a>";
	} else {
		return "Usuario desconocido. <a href='{$rutaApp}/login.php'>Login</a> <a href='{$rutaApp}/register.php'>Registro</a>";
	}
}
?>

<header>
    <div class="cabecera">
        <div class="logo">
            <img src="img/logo.png" alt="Logo EasyCompra">
        </div>
        <div class="nombre">
            <h1>EASY Compra</h1>
        </div> 
        <div class="log">
            <?= mostrarSaludo() ?> 
        </div>   
    </div>
</header>