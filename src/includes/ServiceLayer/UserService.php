<?php

require_once __DIR__ . '/../DAL/UserDAO.php';

class UserService
{
    /**
     * Realiza el proceso de inicio de sesión de un usuario.
     * 
     * @param string $nombreUsuario Nombre de usuario del usuario que intenta iniciar sesión.
     * @param string $password Contraseña del usuario que intenta iniciar sesión.
     * @return UserDTO|false Objeto UserDTO que representa al usuario si el inicio de sesión es exitoso, o false si las credenciales son incorrectas.
     */
    public static function login($nombreUsuario, $email, $password)
    {
        $user = UserDAO::GetUser($nombreUsuario);

        if ($user && password_verify($password, $user->Password())  && $user->Correo() === $email) {
            return $user;
        }

        return false;
    }

    /**
     * Realiza el proceso de registro de un usuario.
     * 
     * @param string $nombreUsuario Nombre de usuario del usuario que intenta registrarse.
     * @param string $email Correo electrónico del usuario que intenta registrarse.
     * @param string $password Contraseña del usuario que intenta registrarse.
     * @param string $descripcion Descripción del usuario que intenta registrarse.
     * @param bool $administrador Indica si el usuario que intenta registrarse es administrador.
     * @param array $file Archivo de imagen del usuario que intenta registrarse.
     * @return bool true si el registro es exitoso, o false si el usuario ya existe.
     */
    public static function register($nombreUsuario, $email, $password, $descripcion, $administrador, $file)
    {
        if (UserDAO::GetUserByEmail($email) || UserDAO::GetUser($nombreUsuario)) {
            return false;
        } else {
            $typo = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $blobi = file_get_contents($file['tmp_name']);
            return UserDAO::InsertUser($nombreUsuario, $password, $email, $descripcion, $administrador, $typo, $blobi);
        }
    }

    /**
     * Obtiene un usuario por su nombre de usuario.
     * 
     * @param string $nombreUsuario Nombre de usuario del usuario a obtener.
     * @return UserDTO|null Objeto UserDTO que representa al usuario si existe, o null si no existe.
     */
    public static function getUser($email)
    {
        return UserDAO::GetUserByEmail($email);
    }

    /**
     * Cambia la contraseña de un usuario.
     * 
     * @param UserDTO $user Usuario al que se le cambiará la contraseña.
     * @param string $currentPassword Contraseña actual del usuario.
     * @param string $newPassword Nueva contraseña del usuario.
     * @return bool true si la contraseña se cambió correctamente, o false si la contraseña actual es incorrecta.
     */
    public static function changePassword($user, $currentPassword, $newPassword)
    {
        if (password_verify($currentPassword, $user->Password())) {
            return UserDAO::ChangePassword($user->Id(), password_hash($newPassword, PASSWORD_DEFAULT));
        }

        return false;
    }

    /**
     * Obtiene todos los usuarios de la base de datos.
     * 
     * @return UserDTO[] Lista de objetos UserDTO que representan a los usuarios.
     */
    public static function getallusers()
    {
        return UserDAO::GetAllUsers();
    }

    /**
     * Elimina un usuario de la base de datos.
     * 
     * @param int $id ID del usuario a eliminar.
     * @return bool true si el usuario se eliminó correctamente, o false si no se pudo eliminar.
     */
    public static function deleteUser($id)
    {
        return UserDAO::DeleteUser($id);
    }

    /**
     * Convierte un usuario en administrador.
     * 
     * @param int $id ID del usuario a convertir en administrador.
     * @return bool true si el usuario se convirtió en administrador, o false si no se pudo convertir.
     */
    public static function setuseradmin($id)
    {
        return UserDAO::SetUserAdmin($id);
    }

    /**
     * Quita los permisos de administrador a un usuario.
     * 
     * @param int $id ID del usuario al que se le quitarán los permisos de administrador.
     * @return bool true si se quitaron los permisos de administrador, o false si no se pudieron quitar.
     */
    public static function unsetuseradmin($id)
    {
        return UserDAO::UnsetUserAdmin($id);
    }
}