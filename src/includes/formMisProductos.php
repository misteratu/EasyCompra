<?php

require_once __DIR__ . '/DTO/ProductoDTO.php';
require_once __DIR__ . '/ServiceLayer/ProductService.php';
require_once __DIR__ . '/ServiceLayer/UserService.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/form.php';


class formMisProductos implements Form
{

    public function generateForm()
    {
        // Variable para almacenar el contenido HTML generado
        $htmlContent = '';

        if (isset($_SESSION['email'])) {

            $owner = UserService::getUser($_SESSION['email']);

            $dueno_id = $owner->Id();

            // Realizar la b�squeda de productos
            $productos = ProductService::GetMyProductos($dueno_id);

            // Verificar si se encontraron productos
            if (!empty($productos)) {

                // Iniciar el contador de productos mostrados
                $counter = 0;

                // Recorrer los productos y generar el contenido HTML
                foreach ($productos as $producto) {
                    // Si el contador es un m�ltiplo de 3, comenzar una nueva fila
                    if ($counter % 3 == 0) {
                        $htmlContent .= '<div class="product-row">';
                    }

                    // Agregar la informaci�n del producto al contenido HTML
                    $htmlContent .= '<div class="product" onclick="showProduct(' . $producto->Id() . ')">';
                    $htmlContent .= '<img src="data:image/' . $producto->Typo() . ';base64,' . base64_encode($producto->Blobi()) . '" alt="' . $producto->Name() . '">';
                    $htmlContent .= '<h3>' . $producto->Name() . " : " . $producto->Precio() . " €" . '</h3>';
                    $htmlContent .= '<form action="./eliminar.php" method="POST">';
                    $htmlContent .= '<input type="hidden" name="product_id" value="' . $producto->Id() . '">';
                    $htmlContent .= '<button type="submit" class="botonForm2">Eliminar</button>';
                    $htmlContent .= '</form>';
                    $htmlContent .= '</div>';

                    // Script JavaScript para mostrar el producto al hacer clic 

                    $htmlContent .= '<script src="JS/editProduct.js"></script>';

                    // Incrementar el contador de productos mostrados
                    $counter++;

                    // Si el contador es un m�ltiplo de 3 o si es el �ltimo producto, cerrar la fila
                    if ($counter % 3 == 0 || $counter == count($productos)) {
                        $htmlContent .= '</div>';
                    }
                }
            }
        } else {
            $htmlContent .= '<h2>Usuario no logeado</h2>';
        }

        // Devolver el contenido HTML generado
        return $htmlContent;
    }



    public function processForm()
    {
        // No hacemos nada
    }
}
