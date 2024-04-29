<?php

require_once __DIR__ . '/../DTO/UserDTO.php';
require_once __DIR__ . '/../config.php'; // Importar la clase Config.php

class UserDAO
{
    /**
     * Obtiene un usuario por su nombre de usuario.
     * 
     * @param string $userName Nombre de usuario del usuario a buscar.
     * @return UserDTO Objeto UserDTO que representa al usuario encontrado, o null si no se encuentra ningún usuario con el nombre proporcionado.
     */
    public static function GetUser($userName)
    {
        // Obtener la conexión mysqli
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparamos la consulta SQL para obtener el usuario por su username
        $sql = "SELECT * FROM Usuario WHERE username = ?";

        // Preparamos la declaracion
        $stmt = $conn->prepare($sql);

        // Vinculamos parametros
        $stmt->bind_param("s", $userName);

        //Ejecutamos la consulta
        $stmt->execute();

        // Ejecutar la consulta
        $result = $stmt->get_result();

        if ($result) {
            if ($result->num_rows == 0) {
                // No se encontró ningún usuario con el nombre proporcionado
                return false;
            } else {
                $row = $result->fetch_assoc();
                // Crear un objeto UserDTO con los datos recuperados de la BD
                $user = new UserDTO($row['id'], $row['username'], $row['pass'], $row['correo'], $row['descripcion'], $row['administrador'], $row['typo'], $row['blobi']);
                $result->free();
                return $user;
            }
            $result->free();
        } else {
            echo "Error SQL ({$conn->errno}):  {$conn->error}";
            exit();
        }
    }

    public static function GetUserByEmail($email)
    {
        // Obtener la conexión mysqli
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparamos la consulta SQL para obtener el usuario por su email
        $sql = "SELECT * FROM Usuario WHERE correo = ?";

        // Preparamos la declaracion
        $stmt = $conn->prepare($sql);

        // Vinculamos parametros
        $stmt->bind_param("s", $email);

        //Ejecutamos la consulta
        $stmt->execute();

        // Ejecutar la consulta
        $result = $stmt->get_result();

        if ($result) {
            if ($result->num_rows == 0) {
                // No se encontró ningún usuario con el nombre proporcionado
                return false;
            } else {
                $row = $result->fetch_assoc();
                // Crear un objeto UserDTO con los datos recuperados de la BD
                $user = new UserDTO($row['id'], $row['username'], $row['pass'], $row['correo'], $row['descripcion'], $row['administrador'], $row['typo'], $row['blobi']);
                $result->free();
                return $user;
            }
            $result->free();
        } else {
            echo "Error SQL ({$conn->errno}):  {$conn->error}";
            exit();
        }
    }

    /**
     * Inserta un nuevo usuario en la base de datos.
     * 
     * @param string $username Nombre de usuario del nuevo usuario.
     * @param string $pass Contraseña del nuevo usuario.
     * @param string $correo Correo electrónico del nuevo usuario.
     * @param string $descripcion Descripción del nuevo usuario.
     * @param int $administrador 1 si el nuevo usuario es administrador, 0 si no lo es.
     * @param string $typo Typo de la imagen
     * @param longblob $blobi Datos binarios de la imagen
     * @return bool true si el usuario se insertó correctamente, false si no.
     */
    public static function InsertUser($username, $pass, $correo, $descripcion, $administrador, $typo, $blobi)
    {
        // Obtener la conexión mysqli
        $conn = Aplicacion::getInstance()->getConexionBd();
        // Obtine datos de la consulta escapados y hash del password
        $nombreUsuario = $conn->real_escape_string($username);
        $password = password_hash($pass, PASSWORD_DEFAULT);
        $email = $conn->real_escape_string($correo);
        $description = $conn->real_escape_string($descripcion);

        // Insertar el usuario en la BD
        $sql = "INSERT INTO Usuario (username, pass, correo, descripcion, administrador, typo, blobi) VALUES (?,?,?,?,?,?,?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ssssiss", $nombreUsuario, $password, $email, $description, $administrador, $typo, $blobi);

        $stmt->execute();

        // Verificar si se insertó el usuario correctamente
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            echo "Error SQL ({$conn->errno}):  {$conn->error}";
            exit();
        }
    }

    /**
     * Actualiza el password de un usuario en la base de datos.
     * 
     * @param int $id ID del usuario cuyo password se va a actualizar.
     * @param string $newPassword Nuevo password del usuario.
     * @return bool true si el password se actualizó correctamente, false si no.
     */
    public static function ChangePassword($id, $newPassword)
    {
        // Obtener la conexión mysqli
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Actualizar el password del usuario en la BD
        $sql = "UPDATE Usuario SET pass = ? WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("si", $newPassword, $id);

        $stmt->execute();

        // Verificar si se actualizó el password correctamente
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            echo "Error SQL ({$conn->errno}):  {$conn->error}";
            exit();
        }
    }

    /**
     * Obtiene todos los usuarios de la base de datos.
     * 
     * @return UserDTO[] Todos los usuarios.
     */
    public static function GetAllUsers()
    {
        // Obtener la conexión mysqli
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparar la consulta SQL para obtener todos los usuarios
        $sql = "SELECT * FROM Usuario";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado de la consulta
        $result = $stmt->get_result();

        // Crear un array para almacenar los usuarios
        $users = array();

        // Verificar si se obtuvieron resultados
        if ($result) {
            // Recorrer los resultados y crear objetos UserDTO
            while ($row = $result->fetch_assoc()) {
                $user = new UserDTO($row['id'], $row['username'], $row['pass'], $row['correo'], $row['descripcion'], $row['administrador'], $row['typo'], $row['blobi']);
                $users[] = $user;
            }

            // Liberar el resultado
            $result->free();
        } else {
            echo "Error SQL ({$conn->errno}):  {$conn->error}";
            exit();
        }

        return $users;
    }

    /**
     * Elimina un usuario de la base de datos.
     * 
     * @param int $id ID del usuario a eliminar.
     * @return bool true si el usuario se eliminó correctamente, false si no.
     */
    public static function DeleteUser($id)
    {
        // Obtener la conexión mysqli
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparar la consulta SQL para eliminar un usuario
        $sql = "DELETE FROM Usuario WHERE id = ?";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        $stmt->bind_param("i", $id);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si se eliminó el usuario correctamente
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            echo "Error SQL ({$conn->errno}):  {$conn->error}";
            exit();
        }
    }

    /**
     * Hace admin a un usuario.
     * 
     * @param int $id ID del usuario a hacer admin.
     * @return bool true si el usuario se hizo admin correctamente, false si no.
     */
    public static function SetUserAdmin($id)
    {
        // Obtener la conexión mysqli
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparar la consulta SQL para hacer admin a un usuario
        $sql = "UPDATE Usuario SET administrador = 1 WHERE id = ?";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        $stmt->bind_param("i", $id);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si se hizo admin al usuario correctamente
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            echo "Error SQL ({$conn->errno}):  {$conn->error}";
            exit();
        }
    }

    /**
     * Quita admin a un usuario.
     * 
     * @param int $id ID del usuario a quitar admin.
     * @return bool true si el usuario se quitó admin correctamente, false si no.
     */
    public static function UnsetUserAdmin($id)
    {
        // Obtener la conexión mysqli
        $conn = Aplicacion::getInstance()->getConexionBd();

        // Preparar la consulta SQL para quitar admin a un usuario
        $sql = "UPDATE Usuario SET administrador = 0 WHERE id = ?";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        $stmt->bind_param("i", $id);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si se quitó admin al usuario correctamente
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            echo "Error SQL ({$conn->errno}):  {$conn->error}";
            exit();
        }
    }
}
