<?php

class ProductoDTO
{
    private $id; // ID del producto
    private $dueno_id; // ID del dueño del producto
    private $name; // Nombre del producto
    private $descripcion; // Descripción del producto
    private $precio; // Precio del producto
    private $categoria; // Nombre de la categoría del producto
    private $cambio; // Estado del producto (en cambio o no)
    private $typo; // Tipo de la imagen
    private $blobi; // Imagen del producto

    /**
	 * Constructor de la clase ProductoDTO.
	 * 
	 * @param int $id ID del producto.
	 * @param int $dueno_id ID del dueño del producto.
	 * @param string $name Nombre del producto.
	 * @param string $descripcion Descripción del producto.
	 * @param float $precio Precio del producto.
	 * @param int $categoria ID de la categoría del producto.
	 * @param bool $cambio Estado del producto (true si está en cambio, false si no lo está).
     * @param string $typo tipo de la imagen.
     * @param longblob $blobi imagen del producto.
	 */
    public function __construct($id, $dueno_id, $name, $descripcion, $precio, $categoria, $cambio, $typo, $blobi)
    {
        $this->id = $id;
        $this->dueno_id = $dueno_id;
        $this->name = $name;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->categoria = $categoria;
        $this->cambio = $cambio;
        $this->typo = $typo;
        $this->blobi = $blobi;
    }

    /**
	 * Método getter para obtener el ID del producto.
	 * 
	 * @return int ID del producto.
	 */
    public function Id()
    {
        return $this->id;
    }

    /**
	 * Método getter para obtener el ID del dueño del producto.
	 * 
	 * @return int ID del dueño del producto.
	 */
    public function Dueno_id()
    {
        return $this->dueno_id;
    }

    /**
	 * Método getter para obtener el nombre del producto.
	 * 
	 * @return string Nombre del producto.
	 */
    public function Name()
    {
        return $this->name;
    }

    /**
	 * Método getter para obtener la descripción del producto.
	 * 
	 * @return string Descripción del producto.
	 */
    public function Descripcion()
    {
        return $this->descripcion;
    }

    /**
	 * Método getter para obtener el precio del producto.
	 * 
	 * @return float Precio del producto.
	 */
    public function Precio()
    {
        return $this->precio;
    }

    /**
	 * Método getter para obtener el ID de la categoría del producto.
	 * 
	 * @return int ID de la categoría del producto.
	 */
    public function Categoria()
    {
        return $this->categoria;
    }

    /**
	 * Método getter para obtener el estado del producto (en cambio o no).
	 * 
	 * @return bool Estado del producto (true si está en cambio, false si no lo está).
	 */
    public function Cambio()
    {
        return $this->cambio;
    }

    /**
     * Método getter para obtener el tipo de la imagen.
     * 
     * @return string Tipo de la imagen.
     */
    public function Typo()
    {
        return $this->typo;
    }

    /**
     * Método getter para obtener la imagen del producto.
     * 
     * @return longblob Imagen del producto.
     */
    public function Blobi()
    {
        return $this->blobi;
    }
}
?>
