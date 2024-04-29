<?php

require_once __DIR__ . '/../DAL/ProductoDAO.php';

class ProductService
{
	/**
	 * Realiza una búsqueda de productos con filtros opcionales.
	 * 
	 * @param string|null $productName Nombre del producto a buscar (opcional).
	 * @param int|null $category ID de la categoría del producto a buscar (opcional).
	 * @param float|null $minPrice Precio mínimo de los productos a buscar (opcional).
	 * @param float|null $maxPrice Precio máximo de los productos a buscar (opcional).
	 * @return array Array de objetos ProductoDTO que representan los productos encontrados.
	 */
	public static function search($productName = null, $category = null, $minPrice = null, $maxPrice = null)
	{
		return ProductoDAO::GetProductosFiltered($productName, $category, $minPrice, $maxPrice);
	}

	/**
	 * Realiza una búsqueda de un producto por su ID.
	 * 
	 * @param int $id ID del producto a buscar.
	 * @return ProductoDTO|null Objeto ProductoDTO que representa el producto encontrado, o null si no se encuentra.
	 */
	public static function searchById($id)
	{
		return ProductoDAO::GetProducto($id);
	}

	/**
	 * Inserta un nuevo producto en la base de datos.
	 * 
	 * @param int $dueno_id ID del dueño del producto.
	 * @param string $name Nombre del producto.
	 * @param string $descripcion Descripción del producto.
	 * @param float $precio Precio del producto.
	 * @param int $categoria categoría del producto.
	 * @param bool $cambio boolean para decir si accepta un cambio del producto.
	 * @param array $file Array con la información del archivo a insertar.
	 * @return int|false ID del producto insertado si la inserción fue exitosa, false en caso contrario.
	 */
	public static function add($dueno_id, $name, $descripcion, $precio, $categoria, $cambio, $file)
	{
		$typo = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$blobi = file_get_contents($file['tmp_name']);
		return ProductoDAO::InsertProducto($dueno_id, $name, $descripcion, $precio, $categoria, $cambio, $typo, $blobi);
	}


	/**
	 * Recupera los productos de un usuario.
	 * 
	 * @param int $dueno_id ID del dueño de los productos.
	 * @return array Array de objetos ProductoDTO que representan los productos encontrados.
	 */
	public static function GetMyProductos($dueno_id)
	{
		return ProductoDAO::GetMyProductos($dueno_id);
	}

	 /**
     * Elimina un producto por su ID.
     * 
     * @param int $product_id ID del producto a eliminar.
     * @return bool true si la eliminación fue exitosa, false si falló.
     */
    public static function deleteProduct($product_id)
    {
        // Utilizar ProductoDAO para eliminar el producto
        $deleted = ProductoDAO::DeleteProducto($product_id);
        return $deleted;
    }

	/**
	 * Edita un producto en la base de datos.
	 * 
	 * @param int $id ID del producto a editar.
	 * @param string $productName Nombre del producto.
	 * @param int $category ID de la categoría del producto.
	 * @param string $descripcion Descripción del producto.
	 * @param float $precio Precio del producto.
	 * @param bool $cambio boolean para decir si accepta un cambio del producto.
	 * @param string $typo tipo de la imagen.
	 * @param array $file Array con la información del archivo a insertar.
	 * @return bool true si la edición fue exitosa, false si falló.
	 */
	public static function editProduct($id, $productName, $descripcion, $precio, $category, $cambio, $file){
		$typo = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$blobi = file_get_contents($file['tmp_name']);
		return ProductoDAO::EditProducto($id, $productName, $descripcion, $precio, $category, $cambio, $typo, $blobi);
	}

	/**
	 * Edita un producto en la base de datos sin cambiar la imagen.
	 * 
	 * @param int $id ID del producto a editar.
	 * @param string $productName Nombre del producto.
	 * @param int $category ID de la categoría del producto.
	 * @param string $descripcion Descripción del producto.
	 * @param float $precio Precio del producto.
	 * @param bool $cambio boolean para decir si accepta un cambio del producto.
	 * @return bool true si la edición fue exitosa, false si falló.
	 */
	public static function editProductWithoutImage($id, $productName, $descripcion, $precio, $category, $cambio){
		$product = ProductoDAO::GetProducto($id)[0];
		if ($product->Precio() == $precio && $product->Name() == $productName && $product->Descripcion() == $descripcion && $product->Categoria() == $category && $product->Cambio() == $cambio){
			return true;
		} else {
			return ProductoDAO::EditProductoWithoutImage($id, $productName, $descripcion, $precio, $category, $cambio);
		}
	}
}
