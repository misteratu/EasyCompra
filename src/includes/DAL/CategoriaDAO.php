<?php

require_once __DIR__ . '/../DTO/CategoriaDTO.php'; // Importar la clase CategoriaDTO.php
require_once __DIR__ . '/../config.php'; // Importar la clase Config.php

class CategoriaDAO
{
    /**
     * Obtiene todas las categorías.
     * 
     * @return CategoriaDTO[]|false Array de objetos CategoriaDTO correspondientes a todas las categorías, o false si ocurre un error.
     */
    public static function GetAllCategories()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparar la consulta SQL para obtener el producto por su ID
        $sql = "SELECT id, nombre FROM Categoria"; // Modificado para reflejar la estructura de tu base de datos
        
        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener resultados
        $result = $stmt->get_result();
        
        if ($result) {
            $categorias = array();
            // Recorrer los resultados y crear objetos ProductoDTO
            while ($row = $result->fetch_assoc()) {
                $categoria = new CategoriaDTO($row['id'], $row['nombre']);
                $categorias[] = $categoria;
            }

            return $categorias;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    /**
     * Inserta una categoría en la base de datos.
     * 
     * @param string $nombre Nombre de la categoría a insertar.
     * @return bool true si la categoría se insertó correctamente, false si no.
     */
    public static function InsertCategory($nombre)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparar la consulta SQL para insertar una categoría
        $sql = "INSERT INTO Categoria (nombre) VALUES (?)";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        $stmt->bind_param("s", $nombre);

        // Ejecutar la consulta
        $stmt->execute();

        // Comprobar si la consulta se ejecutó correctamente
        if ($stmt->affected_rows === 1) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    /**
     * Elimina una categoría de la base de datos.
     * 
     * @param int $id ID de la categoría a eliminar.
     * @return bool true si la categoría se eliminó correctamente, false si no.
     */
    public static function DeleteCategory($id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparar la consulta SQL para eliminar una categoría
        $sql = "DELETE FROM Categoria WHERE id = ?";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        $stmt->bind_param("i", $id);

        // Ejecutar la consulta
        $stmt->execute();

        // Comprobar si la consulta se ejecutó correctamente
        if ($stmt->affected_rows === 1) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
}