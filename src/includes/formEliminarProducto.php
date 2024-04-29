<?php

require_once __DIR__ . '/DTO/ProductoDTO.php';
require_once __DIR__ . '/ServiceLayer/ProductService.php';
require_once __DIR__ . '/form.php';

class formEliminarProducto implements Form
{
    public function generateForm()
    {
        // No se necesita generar un formulario de eliminación aquí
    }

    public function processForm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
            $product_id = $_POST['product_id'];
            // Verificar si el producto existe antes de eliminarlo
            $product = ProductService::searchById($product_id);
            if ($product) {
                // Verificar si el usuario tiene permiso para eliminar este producto (comparar con el usuario actual)
                // Lógica para eliminar el producto utilizando ProductService
                ProductService::deleteProduct($product_id);
                // Redirigir a la página de Mis Productos después de la eliminación
                header("Location: ./misProductos.php");
                exit();
            } else {
                // Manejar el caso en el que el producto no existe
                // Por ejemplo, mostrar un mensaje de error o redirigir a otra página
                header("Location: /error.php?message=Producto no encontrado");
                exit();
            }
        }
    }
}
?>