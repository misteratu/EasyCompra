<?php

// Incluye los archivos necesarios
require_once __DIR__ . '/DTO/ProductoDTO.php';
require_once __DIR__ . '/ServiceLayer/ProductService.php';
require_once __DIR__ . '/ServiceLayer/CategoryService.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/form.php';

/**
 * Clase para generar y procesar un formulario de modificación de producto.
 */
class FormEdit implements Form
{
    // Método para generar el formulario de edición
    public function generateForm()
    {
        $htmlContent = '';

        // Obtén todas las categorías disponibles
        $categories = CategoryService::getAllCategories();

        if (isset($_GET['producto_id'])) {
            $producto_id = $_GET['producto_id'];

            // Busca el producto por su ID
            $productos = ProductService::searchById($producto_id);

            if (!empty($productos)) {
                foreach ($productos as $producto) {
                    // Genera el formulario HTML
                    $htmlContent .= '<form method="post" action="" class="formEdit" enctype="multipart/form-data">';
                    $htmlContent .= '<div class="mostrarProduct">';
                    $htmlContent .= '<div class="imagenProduct">';
                    $htmlContent .= '<img src="data:image/' . $producto->Typo() . ';base64,' . base64_encode($producto->Blobi()) . '" alt="' . $producto->Name() . '">';
                    $htmlContent .= '</div>';
                    $htmlContent .= '<div class="formFields" style="margin-left: 20px;">';
                    $htmlContent .= '<div>';
                    $htmlContent .= '<label for="Nombre">Nombre del producto:</label><br>';
                    $htmlContent .= '<textarea id="Nombre" name="Nombre">' . $producto->Name() . '</textarea>';
                    $htmlContent .= '</div>';
                    $htmlContent .= '<div>';
                    $htmlContent .= '<label for="price">Precio del producto:</label><br>';
                    $htmlContent .= '<input type="number" id="price" name="price" value="' . $producto->Precio() . '">';
                    $htmlContent .= '</div>';
                    $htmlContent .= '<div>';
                    $htmlContent .= '<label for="descripcion">Descripción del producto:</label><br>';
                    $htmlContent .= '<textarea id="descripcion" name="description">' . $producto->Descripcion() . '</textarea>';
                    $htmlContent .= '</div>';
                    $htmlContent .= '<div class="campoForm">';
                    $htmlContent .= '<label for="image">Seleccione una imagen para cargar:</label>';
                    $htmlContent .= '<input type="file" id="image" name="image" accept="image/*">';
                    $htmlContent .= '</div>';
                    $htmlContent .= '<div>';
                    $htmlContent .= '<label for="category">Categoría del producto:</label>';
                    $htmlContent .= '<select name="category" id="category">';
                    $htmlContent .= '<option value="">Elige una categoría</option>';

                    // Agrega una opción para cada categoría
                    foreach ($categories as $category) {
                        $selected = '';
                        if ($producto->Categoria() == $category->Id()) {
                            // Si la categoría del producto coincide con la opción, selecciónala
                            $selected = 'selected';
                        }
                        $htmlContent .= '<option value="' . $category->Id() . '" ' . $selected . '>' . $category->Nombre() . '</option>';
                    }

                    $htmlContent .= '</select>';
                    $htmlContent .= '</div>';
                    $htmlContent .= '<div>';
                    $htmlContent .= '<label for="exchange">Aceptar intercambio:</label><br>';
                    $checked = $producto->Cambio() == 1 ? 'checked' : '';
                    $htmlContent .= '<input type="checkbox" id="exchange" name="exchange" value="' . 1 . '" ' . $checked . '>';
                    $htmlContent .= '</div>';
                    $htmlContent .= '<button type="submit" class="botonForm">Cargar</button>'; // Botón dentro del formulario
                    $htmlContent .= '</div>'; // Cierre del formFields
                    $htmlContent .= '</div>'; // Cierre del mostrarProduct
                    $htmlContent .= '<input type="hidden" name="producto_id" value="' . $producto->Id() . '">';
                    $htmlContent .= '</form>'; // Cierre del formulario
                }
            } else {
                $htmlContent .= '<p>No se encontraron productos.</p>';
            }
        } else {
            $htmlContent .= '<p>No se ha transmitido ningún ID de producto.</p>';
        }

        return $htmlContent;
    }

    // Método para procesar el formulario enviado
    public function processForm()
    {
        $htmlContent = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Verifica si todos los campos requeridos están presentes
            if (isset($_POST['Nombre']) && isset($_POST['price']) && isset($_POST['description']) && isset($_POST['category'])) {
                // Obtiene los valores de los campos del formulario
                $nombre = $_POST['Nombre'];
                $price = $_POST['price'];
                $description = $_POST['description'];
                $category = $_POST['category'];
                if (isset($_POST['exchange'])) {
                    $exchange = $_POST['exchange'] == '1' ? 1 : 0;
                } else {
                    $exchange = 0;
                } 
                $producto_id = $_POST['producto_id'];
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        // Un archivo ha sido subido, procésalo normalmente
                        $mime = mime_content_type($_FILES['image']['tmp_name']);
                        $allowedTypes = array('image/jpeg');
                        if (in_array($mime, $allowedTypes)) {
                            $files = $_FILES['image'];
                            
                            // Edita el producto con la nueva imagen
                            $productResult = ProductService::editProduct($producto_id, $nombre, $description, $price, $category, $exchange, $files);

                            if ($productResult !== false) {
                                $htmlContent .= '<p class="correctForm">Producto subido.</p>';
                                header("Location: misProductos.php");
                            } else {
                                $htmlContent .= '<p class="errorForm">Error al agregar el producto en la base de datos.</p>';
                            }
                        } else {
                            $htmlContent .= '<p class="errorForm">Tipo de archivo no permitido';
                        }
                } else {
                    // Ningún archivo ha sido subido, conserva la imagen existente
                    $productResult = ProductService::editProductWithoutImage($producto_id, $nombre, $description, $price, $category, $exchange);

                    if ($productResult !== false) {
                        $htmlContent .= '<p class="correctForm">Producto actualizado sin cambiar la imagen.</p>';
                        header("Location: misProductos.php");
                    } else {
                        $htmlContent .= '<p class="errorForm">Error al actualizar el producto en la base de datos.</p>';
                    }
                }
            } else {
                // Si no están presentes todos los campos requeridos, muestra un mensaje de error
                $htmlContent .= '<p>Por favor, complete todos los campos requeridos.</p>';
            }
        }

        return $htmlContent;
    }
}
?>
