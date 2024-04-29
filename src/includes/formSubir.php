<?php
require_once __DIR__ . '/ServiceLayer/ProductService.php';
require_once __DIR__ . '/ServiceLayer/CategoryService.php';
require_once __DIR__ . '/ServiceLayer/UserService.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/form.php';

/**
 * Clase para generar y procesar un formulario de carga de imágenes.
 */
class FormSubir implements Form
{
    /**
     * Genera el formulario HTML para cargar imágenes.
     * 
     * @return string El código HTML del formulario de carga de imágenes.
     */
    public function generateForm()
    {
        $categories = CategoryService::getAllCategories();

        // Inicializa el contenido HTML del formulario con el inicio del formulario.
        $formHTML = '
    <div class="formulario">
        <h2>Subir Producto</h2>
        <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" enctype="multipart/form-data">
            <div class="campoForm">
                <label for="name">Nombre del producto:</label>
                <input type="text" id="name" name="name">
            </div>
            <div class="campoForm">
                <label for="description">Descripción del producto:</label>
                <textarea id="descripcion" name="description"></textarea>
            </div>
            <div class="campoForm">
                <label for="price">Precio del producto:</label>
                <input type="number" id="price" name="price">
            </div>
            <div class="campoForm">
                <label for="category">Categoría del producto:</label>
                <select name="category" id="category">
                    <option value="">Elige una categoría</option>';

        // Añade una opción para cada categoría.
        foreach ($categories as $category) {
            $formHTML .= '<option value="' . $category->Id() . '">' . $category->Nombre() . '</option>'; // Agregar una opción para cada categoría
        }

        // Completa el formulario con el resto de campos y el botón enviar
        $formHTML .= '
                </select>
            </div>
            <div class="campoForm">
                <label for="image">Seleccione una imagen para cargar:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <div class="campoForm">
                <label for="exchange">Acepta intercambio:</label>
                <input id="checkBox" type="checkbox" id="exchange" name="exchange" value="1">
            </div>
            <button type="submit" class="botonForm">Cargar</button>
        </form>
    </div>';

        return $formHTML; // Devuelve el código HTML del formulario
    }

    /**
     * Procesa los datos enviados por el formulario y devuelve el contenido HTML generado.
     * 
     * @return string El contenido HTML generado a partir de los resultados del intento de cargar la imagen.
     */
    public function processForm()
    {
        // Variable para almacenar el contenido HTML generado
        $htmlContent = '';

        // Verifica si se ha enviado un formulario mediante el método POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Verifica si se han enviado datos de producto
            if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['category']) && isset($_FILES['image']) && ($_FILES['image']['size'] != 0)) {
                // Obtiene los datos del formulario
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $category = $_POST['category'];
                $exchange = isset($_POST['exchange']) ? ($_POST['exchange'] == '1' ? 1 : 0) : 0; // Modificado para que el valor sea 0 para falso y 1 para verdadero
                if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $mime = mime_content_type($_FILES['image']['tmp_name']);
                    $allowedTypes = array('image/jpeg');
                    if (in_array($mime, $allowedTypes)) {
                        $files = $_FILES['image'];
                        $owner = UserService::getUser($_SESSION['email']);

                        $owner_id = $owner->Id();

                        $productResult = ProductService::add($owner_id, $name, $description, $price, $category, $exchange, $files);

                        if ($productResult !== false) {
                            $htmlContent .= '<p class="correctForm">Producto subido.</p>';
                        } else {
                            $htmlContent .= '<p class="errorForm">Error al agregar el producto en la base de datos.</p>';
                        }
                    } else {
                        $htmlContent .= '<p class="errorForm">Tipo de archivo no permitido';
                    }
                } else {
                    $htmlContent .= '<p class="errorForm">No se ha subido ninguna imagen';
                }
            } else {
                $htmlContent .= '<p class="errorForm">Por favor, complete todos los campos del formulario del producto.</p>';
            }
        }

        // Devuelve el contenido HTML generado
        return $htmlContent;
    }
}
