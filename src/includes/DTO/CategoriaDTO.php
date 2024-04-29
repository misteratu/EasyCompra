<?php

class CategoriaDTO
{
    private $id; // ID de la categoría
    private $nombre; // Nombre de la categoría

    /**
     * Constructor de la clase CategoriaDTO.
     * 
     * @param int $id ID de la categoría.
     * @param string $nombre Nombre de la categoría.
     */
    public function __construct($id, $nombre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    /**
     * Método getter para obtener el ID de la categoría.
     * 
     * @return int ID de la categoría.
     */
    public function Id()
    {
        return $this->id;
    }

    /**
     * Método getter para obtener el nombre de la categoría.
     * 
     * @return string Nombre de la categoría.
     */
    public function Nombre()
    {
        return $this->nombre;
    }
}