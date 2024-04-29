<?php

class UserDTO
{
    private $id; // ID del usuario
    private $nombreUsuario; // Nombre de usuario del usuario
    private $password; // Contraseña del usuario
    private $correo; // Correo del usuario
    private $descripcion; // Descripcion del usuario
    private $administrador; // Rol del usuario
    private $typo; // Typo de la imagen
    private $blobi; // Datos binarios de la imagen

    /**
     * Constructor de la clase UserDTO.
     * 
     * @param int $id ID del usuario.
     * @param string $nombreUsuario Nombre de usuario del usuario.
     * @param string $password Contraseña del usuario.
     * @param string $correo Correo del usuario.
     * @param string $descripcion Descripcion del usuario.
     * @param string $administrador Rol del usuario.
     * @param string $typo Typo de la imagen.
     * @param longblob $blobi Datos binarios de la imagen.
     */
    public function __construct($id, $nombreUsuario, $password, $correo, $descripcion, $administrador, $typo, $blobi)
    {
        $this->id = $id;
        $this->nombreUsuario = $nombreUsuario;
        $this->password = $password;
        $this->correo = $correo;
        $this->descripcion = $descripcion;
        $this->administrador = $administrador;
        $this->typo = $typo;
        $this->blobi = $blobi;
    }

    /**
     * Método getter para obtener el ID del usuario.
     * 
     * @return int ID del usuario.
     */
    public function Id()
    {
        return $this->id;
    }

    /**
     * Método getter para obtener el nombre de usuario del usuario.
     * 
     * @return string Nombre de usuario del usuario.
     */
    public function NombreUsuario()
    {
        return $this->nombreUsuario;
    }

    /**
     * Método getter para obtener la contraseña del usuario.
     * 
     * @return string Contraseña del usuario.
     */
    public function Password()
    {
        return $this->password;
    }

    /**
     * Método getter para obtener el correo del usuario.
     * 
     * @return string Correo del usuario.
     */
    public function Correo()
    {
        return $this->correo;
    }

    /**
     * Método getter para obtener la descripcion del usuario.
     * 
     * @return string Descripcion del usuario.
     */
    public function Descripcion()
    {
        return $this->descripcion;
    }

    /**
     * Método getter para obtener el rol del usuario.
     * 
     * @return string Rol del usuario.
     */
    public function Administrador()
    {
        return $this->administrador;
    }

    /**
     * Método getter para obtener el typo de la imagen.
     * 
     * @return string Typo de la imagen.
     */
    public function Typo()
    {
        return $this->typo;
    }

    /**
     * Método getter para obtener los datos binarios de la imagen.
     * 
     * @return string Datos binarios de la imagen.
     */
    public function Blobi()
    {
        return $this->blobi;
    }
}
