<?php

require_once __DIR__.'/../DAL/CategoriaDAO.php';

class CategoryService
{   
    /**
     * Obtiene todas las categorías.
     * 
     * @return CategoriaDTO[] Todas las categorías.
     */
    public static function getAllCategories()
    {
        return CategoriaDAO::GetAllCategories();
    }

    /**
     * Inserta una categoría en la base de datos.
     * 
     * @param string $nombre Nombre de la categoría a insertar.
     * @return bool true si la categoría se insertó correctamente, false si no.
     */
    public static function insertCategory($nombre)
    {
        return CategoriaDAO::InsertCategory($nombre);
    }

    /**
     * Elimina una categoría de la base de datos.
     * 
     * @param int $id ID de la categoría a eliminar.
     * @return bool true si la categoría se eliminó correctamente, false si no.
     */
    public static function deleteCategory($id)
    {
        return CategoriaDAO::DeleteCategory($id);
    }
}

?>