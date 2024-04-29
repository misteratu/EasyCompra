<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" type="text/css" href="./CSS/estilo.css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $tituloPagina ?></title>
</head>

<body>
    <?php
    require_once("./includes/Comun/cabecera.php");
    require_once("./includes/Comun/sidebarIzq.php");
    ?>
    <main>
        <?= $contenidoPrincipal ?>
    </main>
</body>

</html>
