<?php
require_once __DIR__ . '/ServiceLayer/ProductService.php';
require_once __DIR__ . '/ServiceLayer/CategoryService.php';
require_once __DIR__ . '/ServiceLayer/UserService.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/form.php';

/**
 * Clase para generar y procesar un formulario para comprar un producto.
 */
class FormComprar implements Form
{
    /**
     * Genera el formulario HTML para cargar imágenes y pagar por un producto.
     * 
     * @return string El código HTML del formulario de compra.
     */
    public function generateForm()
    {
        // Verificar si el ID del producto se pasa como parámetro
        if(isset($_GET['product_id'])) {
            $product_id = htmlspecialchars($_GET['product_id']);
            // Inicializar el contenido HTML del formulario con el inicio del formulario.
            $formHTML = '
            <div class="formulario">
                <h2>Comprar Producto</h2>
                <form method="post" action="">
                    <input type="hidden" name="product_id" value="' . $product_id . '">
                    <div class="campoForm">
                        <label for="card_number">Número de Tarjeta:</label>
                        <input type="text" id="card_number" name="card_number">
                    </div>
                    <div class="campoForm">
                        <label for="expiry_date">Fecha de Caducidad:</label>
                        <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY">
                    </div>
                    <div class="campoForm">
                        <label for="cvv">CVV:</label>
                        <input type="text" id="cvv" name="cvv">
                    </div>
                    <button type="submit" name="submit" class="botonForm">Pagar</button>
                </form>
            </div>';
        } else {
            // Si el ID del producto no se pasa, no mostrar el formulario y no mostrar el mensaje de error
            $formHTML = '';
        }

        return $formHTML; // Devuelve el código HTML del formulario
    }

    /**
     * Procesa los datos enviados por el formulario y devuelve el contenido HTML generado.
     * 
     * @return string El contenido HTML generado.
     */
    public function processForm()
    {
        if(isset($_POST['submit'])) {
            $id = $_POST['product_id'];

            $result = ProductService::deleteProduct($id);

            if ($result) {
                return '<h2>Producto comprado con éxito.</h2>';
            } else {
                return '<h2>Error al comprar el producto.</h2>';
            }
        } else {
            // Si el formulario no ha sido enviado, no hacer nada
            return '';
        }
    }
}
?>
