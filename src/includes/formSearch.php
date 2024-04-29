<?php
require_once __DIR__ . '/ServiceLayer/ProductService.php';
require_once __DIR__ . '/ServiceLayer/CategoryService.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/form.php';

/**
 * Clase para generar y procesar un formulario de búsqueda.
 */
class FormSearch implements Form
{
    /**
     * Genera el formulario HTML de búsqueda.
     * 
     * @return string El código HTML del formulario de búsqueda.
     */
    public function generateForm()
    {
        $categories = CategoryService::getAllCategories();

        $formHTML = '
        <form id="searchForm" method="get" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
            <input id="searchBar" type="text" name="search" id="search" placeholder="Buscar...">
            <select name="category" id="category">
                <option value="">Todas las categorías</option>';
        foreach ($categories as $category) {
            $formHTML .= '<option value="' . $category->Id() . '">' . $category->Nombre() . '</option>'; // Agregar una opción para cada categoría
        }
        $formHTML .= '
            </select>
            <input class="MinMax" type="number" name="minPrice" id="minPrice" placeholder="mínimo">
            <input class="MinMax" id="Min" type="number" name="maxPrice" id="maxPrice" placeholder="máximo">
            <button id="searchButton" type="submit"><span>🔍</span></button>
        </form>';

        return $formHTML; // Devolver el código HTML del formulario
    }

    /**
     * Procesa los datos enviados por el formulario y devuelve el contenido HTML generado.
     * 
     * @return string El contenido HTML generado a partir de los resultados de búsqueda.
     */
    public function processForm()
    {
        // Variable para almacenar el contenido HTML generado
        $htmlContent = '';

        // Verificar si se ha enviado un formulario mediante el método GET
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            // Obtener los datos del formulario
            $search = $_GET["search"] ?? '';
            $category = $_GET["category"] ?? '';
            $minPrice = $_GET["minPrice"] ?? '';
            $maxPrice = $_GET["maxPrice"] ?? '';

            // Realizar la búsqueda de productos
            $productos = ProductService::search($search, $category, $minPrice, $maxPrice);

            // Verificar si se encontraron productos
            if (!empty($productos)) {
                // Agregar el título de los resultados de búsqueda al contenido HTML
                $htmlContent .= '<h2>Resultados de la búsqueda:</h2>';

                // Iniciar el contador de productos mostrados
                $counter = 0;

                // Recorrer los productos y generar el contenido HTML
                foreach ($productos as $producto) {
                    // Si el contador es un múltiplo de 3, comenzar una nueva fila
                    if ($counter % 3 == 0) {
                        $htmlContent .= '<div class="product-row">';
                    }

                    // Agregar la información del producto al contenido HTML
                    $htmlContent .= '<div class="product" onclick="showProduct(' . $producto->Id() . ')">';
                    $htmlContent .= '<img src="data:image/' . $producto->Typo() . ';base64,' . base64_encode($producto->Blobi()) . '" alt="' . $producto->Name() . '">';
                    $htmlContent .= '<h3>' . $producto->Name() . " : " . $producto->Precio() . " €" . '</h3>';
                    $htmlContent .= '</div>';

                    // Script JavaScript para mostrar el producto al hacer clic 

                    $htmlContent .= '<script src="JS/product.js"></script>';

                    // Incrementar el contador de productos mostrados
                    $counter++;

                    // Si el contador es un múltiplo de 3 o si es el último producto, cerrar la fila
                    if ($counter % 3 == 0 || $counter == count($productos)) {
                        $htmlContent .= '</div>';
                    }
                }
            }
        }

        // Devolver el contenido HTML generado
        return $htmlContent;
    }
}
