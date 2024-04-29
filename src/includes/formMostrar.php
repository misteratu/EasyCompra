<?php

require_once __DIR__ . '/DTO/ProductoDTO.php';
require_once __DIR__ . '/ServiceLayer/ProductService.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/form.php';

/**
 * Clase para generar y procesar un formulario de visualización de producto.
 */
class FormMostrar implements Form
{

    /**
     * Genera el formulario HTML de visualización de producto.
     * 
     * @return string El código HTML del formulario de visualización de producto.
     */
    public function generateForm()
    {
        // No es necesario generar un formulario para mostrar un producto
        return '';
    }

    /**
     * Procesa los datos enviados por el formulario y devuelve el contenido HTML generado.
     * 
     * @return string El contenido HTML generado a partir de los resultados de la visualización del producto.
     */
    public function processForm()
    {
        // Variable para almacenar el contenido HTML generado
        $htmlContent = '';

        // Verificar si se ha pasado un ID
        if(isset($_POST['producto_id'])) {
            $producto_id = $_POST['producto_id'];

            // Obtener los detalles del producto desde la base de datos u otra fuente de datos
            $productos = ProductService::searchById($producto_id);

            // Mostrar los detalles del producto
            if(!empty($productos)) {
                foreach($productos as $producto) {
                    $htmlContent .= '<div class="mostrarProduct">';
                    $htmlContent .= '<h1>' . $producto->Name() . '</h1>';
                    $htmlContent .= '<div class="descripcionProduct">';
                    $htmlContent .= '<p>'.$producto->Descripcion(). '</p>';
                    $htmlContent .= '</div>';
                    $htmlContent .= '<h3>Precio: ' . $producto->Precio() . ' €</h3>';
                    $htmlContent .= '<div class="imagenProduct">';
                    $htmlContent .= '<img src="data:image/' . $producto->Typo(). ';base64,' . base64_encode($producto->Blobi()) . '" alt="' . $producto->Name() . '">';
                    $htmlContent .= '</div>';
                }
            } else {
                $htmlContent .= '<p>No se encontraron productos.</p>';
            }
        } else {
            $htmlContent .= '<p>No se ha transmitido ningún ID de producto.</p>';
        }

        // Devuelve el contenido HTML generado
        return $htmlContent;
    }
}
?>
