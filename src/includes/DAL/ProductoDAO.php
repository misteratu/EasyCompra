<?php

require_once __DIR__ . '/../DTO/ProductoDTO.php'; // Importar la clase ProductoDTO.php
require_once __DIR__ . '/../config.php'; // Importar la clase Config.php

class ProductoDAO
{
    /**
     * Obtiene un producto por su ID.
     * 
     * @param int $productId ID del producto.
     * @return ProductoDTO[]|false Array de objetos ProductoDTO correspondientes al ID dado, o false si ocurre un error.
     */
    public static function GetProducto($productId)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparar la consulta SQL para obtener el producto por su ID
        $sql = "SELECT * FROM Producto WHERE id = ?";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        $stmt->bind_param("i", $productId);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener resultados
        $result = $stmt->get_result();

        if ($result) {
            $productos = array();
            // Recorrer los resultados y crear objetos ProductoDTO
            while ($row = $result->fetch_assoc()) {
                $producto = new ProductoDTO($row['id'], $row['dueno_id'], $row['name'], $row['descripcion'], $row['precio'], $row['categoria'], $row['cambio'], $row['typo'], $row['blobi']);
                $productos[] = $producto;
            }

            return $productos;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    /**
     * Obtiene productos filtrados por nombre, categoría y rango de precios.
     * 
     * @param string|null $productName Nombre del producto (opcional).
     * @param int|null $category ID de la categoría del producto (opcional).
     * @param float|null $minPrice Precio mínimo del producto (opcional).
     * @param float|null $maxPrice Precio máximo del producto (opcional).
     * @return ProductoDTO[] Array de objetos ProductoDTO que cumplen con los criterios de filtrado.
     */
    public static function GetProductosFiltered($productName = null, $category = null, $minPrice = null, $maxPrice = null)
    {
        // Obtener la conexión mysqli
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Verificar la conexión
        if ($conn->connect_error) {
            error_log("Error de conexión a la base de datos: " . $conn->connect_error);
            return false;
        }

        // Preparar la consulta SQL para recuperar los productos con filtros opcionales
        $sql = "SELECT * FROM Producto WHERE 1=1";
        $params = array();

        if (!empty($productName)) {
            $sql .= " AND name LIKE ?";
            $params[] = '%' . $productName . '%';
        }

        if (!empty($category)) {
            $sql .= " AND categoria = ?";
            $params[] = $category;
        }

        if (!empty($minPrice)) {
            $sql .= " AND precio >= ?";
            $params[] = $minPrice;
        }

        if (!empty($maxPrice)) {
            $sql .= " AND precio <= ?";
            $params[] = $maxPrice;
        }

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener resultados
        $result = $stmt->get_result();

        $productos = array();

        // Construir un array de objetos ProductoDTO con los datos obtenidos de la base de datos
        while ($row = $result->fetch_assoc()) {
            $producto = new ProductoDTO($row['id'], $row['dueno_id'], $row['name'], $row['descripcion'], $row['precio'], $row['categoria'], $row['cambio'], $row['typo'], $row['blobi']);
            $productos[] = $producto;
        }

        return $productos;
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
     * @param string $typo tipo de la imagen.
     * @param string $blobi imagen del producto.
     * @return int|false ID del producto insertado si la inserción fue exitosa, false en caso contrario.
     */
    public static function InsertProducto($dueno_id, $name, $descripcion, $precio, $categoria, $cambio, $typo, $blobi)
    {
        // Obtener la conexión mysqli
        $conn = Aplicacion::getInstance()->getConexionBd();

        $duennoId = $conn->real_escape_string($dueno_id);
        $nombre = $conn->real_escape_string($name);
        $description = $conn->real_escape_string($descripcion);
        $price = $conn->real_escape_string($precio);
        $category = $conn->real_escape_string($categoria);

        // Insertar el producto en la BD
        $sql = "INSERT INTO Producto (dueno_id, name, descripcion, precio, categoria, cambio, typo, blobi) VALUES (?,?,?,?,?,?,?,?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("issdiiss", $duennoId, $nombre, $description, $price, $category, $cambio, $typo, $blobi);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si la inserción fue exitosa
        if ($stmt->affected_rows > 0) {
            // Obtener el ID del último registro insertado
            $last_id = $conn->insert_id;
            return $last_id;
        } else {
            // Si la inserción no fue exitosa, retourner false
            return false;
        }
    }

    /**
     * Recupera los productos de un usuario.
     * 
     * @param int $dueno_id ID del dueño de los productos.
     * @return array Array de objetos ProductoDTO que representan los productos encontrados.
     */
    public static function GetMyProductos($dueno_id)
    {
        // Obtener la conexión mysqli
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Verificar la conexión
        if ($conn->connect_error) {
            error_log("Error de conexión a la base de datos: " . $conn->connect_error);
            return false;
        }

        $sql = "SELECT * FROM Producto WHERE dueno_id = $dueno_id";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        // Ejecutar la consulta
        $stmt->execute();

        // Obtener resultados
        $result = $stmt->get_result();

        $productos = array();

        // Construir un array de objetos ProductoDTO con los datos obtenidos de la base de datos
        while ($row = $result->fetch_assoc()) {
            $producto = new ProductoDTO($row['id'], $row['dueno_id'], $row['name'], $row['descripcion'], $row['precio'], $row['categoria'], $row['cambio'], $row['typo'], $row['blobi']);
            $productos[] = $producto;
        }

        return $productos;
    }

    /**
     * Elimina un producto de la base de datos.
     * 
     * @param int $productId ID del producto a eliminar.
     * @return bool true si la eliminación fue exitosa, false en caso contrario.
     */
    public static function deleteProducto($productId)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparar la consulta SQL para eliminar el producto por su ID
        $sql = "DELETE FROM Producto WHERE id = ?";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        $stmt->bind_param("i", $productId);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si la eliminación fue exitosa
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Edita un producto en la base de datos.
     * 
     * @param int $productId ID del producto a editar.
     * @param string $name Nombre del producto.
     * @param string $descripcion Descripción del producto.
     * @param float $precio Precio del producto.
     * @param int $categoria categoría del producto.
     * @param bool $cambio boolean para decir si accepta un cambio del producto.
     * @param string $typo tipo de la imagen.
     * @param string $blobi imagen del producto.
     * @return bool true si la edición fue exitosa, false en caso contrario.
     */
    public static function EditProducto($productId, $name, $descripcion, $precio, $categoria, $cambio, $typo, $blobi)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $nombre = $conn->real_escape_string($name);
        $descripcion = $conn->real_escape_string($descripcion);
        $precio = $conn->real_escape_string($precio);
        $categoria = $conn->real_escape_string($categoria);
        $cambio = $conn->real_escape_string($cambio);
        
        // Actualizar el producto en la BD
        $sql = "UPDATE Producto SET name = ?, descripcion = ?, precio = ?, categoria = ?, cambio = ?, typo = ?, blobi = ? WHERE id = ?";

        $stmt = $conn->prepare($sql);

        // Enlazar los parámetros
        $stmt->bind_param("ssdsisss", $nombre, $descripcion, $precio, $categoria, $cambio, $typo, $blobi, $productId);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si la actualización fue exitosa
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Edita un producto en la base de datos sin cambiar la imagen.
     * 
     * @param int $productId ID del producto a editar.
     * @param string $name Nombre del producto.
     * @param string $descripcion Descripción del producto.
     * @param float $precio Precio del producto.
     * @param int $categoria categoría del producto.
     * @param bool $cambio boolean para decir si accepta un cambio del producto.
     * @return bool true si la edición fue exitosa, false en caso contrario.
     */
    public static function EditProductoWithoutImage($productId, $name, $descripcion, $precio, $categoria, $cambio)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $nombre = $conn->real_escape_string($name);
        $description = $conn->real_escape_string($descripcion);
        $price = $conn->real_escape_string($precio);
        $category = $conn->real_escape_string($categoria);

        // Insertar el producto en la BD
        $sql = "UPDATE Producto SET name = ?, descripcion = ?, precio = ?, categoria = ?, cambio = ? WHERE id = ?";

        $stmt = $conn->prepare($sql);

        // Define the data types of the parameters
        $stmt->bind_param("ssdsdi", $nombre, $description, $price, $category, $cambio, $productId);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si la inserción fue exitosa
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}
