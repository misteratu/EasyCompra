<?php

require_once __DIR__ . '/DTO/UserDTO.php';
require_once __DIR__ . '/ServiceLayer/UserService.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/form.php';
require_once __DIR__ . '/utils.php';

class FormPerfil implements Form
{
    private $erroresFormulario = [];

    /**
     * Genera el formulario para mostrar el perfil del usuario.
     *
     * @return string El contenido HTML generado.
     */
    public function generateForm(): string
    {
        if (!isset($_SESSION["email"])) {
            return '<p>Correo electrónico no encontrado en la sesión</p>';
        }

        $email = $_SESSION["email"];
        try {
            $user = UserService::getUser($email);
            $action = $_SERVER['PHP_SELF'];

            $htmlContent = '<div class="profile-container">';
            $htmlContent .= '<div class="profile-image-container">';
            $htmlContent .= '<img class="profile-image" src="data:image/' . $user->Typo() . ';base64,' . base64_encode($user->Blobi()) . '" alt="' . $user->NombreUsuario() . '">'; // Agregar una clase para la imagen
            $htmlContent .= '</div>';
            $htmlContent .= '<div class="profile-info-container">';
            $htmlContent .= '<h1>' . htmlspecialchars($user->NombreUsuario()) . '</h1>';
            $htmlContent .= '<h3>' . htmlspecialchars($user->Correo()) . '</h3>';
            $htmlContent .= '<p class="description">' . htmlspecialchars($user->Descripcion()) . '</p>';
            $htmlContent .= '<a href="misProductos.php">Ver sus productos</a>';
            $htmlContent .= '</div>';
            $htmlContent .= '</div>';

            // Sección para cambiar la contraseña
            $htmlContent .= '<div class="formulario">';
            $htmlContent .= '<h2>Cambiar contraseña</h2>';
            $htmlContent .= '<form method="post" action="' . $action . '" enctype="multipart/form-data">';
            $htmlContent .= '<div class="campoForm">';
            $htmlContent .= '<label for="current-password">Contraseña actual:</label>';
            $htmlContent .= '<input type="password" id="current-password" name="current-password" required>';
            $htmlContent .= '</div>';
            $htmlContent .= '<div class="campoForm">';
            $htmlContent .= '<label for="new-password">Nueva contraseña:</label>';
            $htmlContent .= '<input type="password" id="new-password" name="new-password" required>';
            $htmlContent .= '</div>';
            $htmlContent .= '<div class="campoForm">';
            $htmlContent .= '<label for="confirm-password">Confirmar nueva contraseña:</label>';
            $htmlContent .= '<input type="password" id="confirm-password" name="confirm-password" required>';
            $htmlContent .= '</div>';
            $htmlContent .= '<button type="submit" class="botonForm">Cambiar contraseña</button>';
            $htmlContent .= '</form>';
            $htmlContent .= '</div>';

            return $htmlContent;
        } catch (Exception $e) {
            error_log('Error al recuperar los datos del usuario: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Procesa el formulario.
     */
    public function processForm()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Verificar si los campos de contraseña fueron enviados
            if (isset($_POST['current-password']) && isset($_POST['new-password']) && isset($_POST['confirm-password'])) {
                $currentPassword = $_POST['current-password'];
                $newPassword = $_POST['new-password'];
                $confirmPassword = $_POST['confirm-password'];

                // Verificar si la nueva contraseña y su confirmación coinciden
                $this->comprobarSimilitude($newPassword, $confirmPassword);
                if (isset($this->erroresFormulario['confirm-password'])) {
                    return '<p class="error">' . $this->erroresFormulario['confirm-password'] . '</p>';
                }

                // Verificar la longitud de la nueva contraseña
                $this->comprobarPassword($newPassword);

                // Si la verificación de la longitud de la contraseña pasa
                if (!isset($this->erroresFormulario['password'])) {
                    $user = UserService::getUser($_SESSION['email']);
                    $change = UserService::changePassword($user, $currentPassword, $newPassword);
                    if ($change) {
                        return '<p class="success">¡Contraseña cambiada con éxito!</p>';
                    } else {
                        return '<p class="error">¡Contraseña actual incorrecta!</p>';
                    }
                } else {
                    return '<p class="error">' . $this->erroresFormulario['password'] . '</p>';
                }
            } else {
                return '<p class="error">¡Faltan campos de contraseña!</p>';
            }
        }
    }


    /**
     * Comprueba si la contraseña es válida.
     *
     * @param string $password La contraseña a comprobar.
     */
    private function comprobarPassword($password)
    {
        if (!$password || empty($password = trim($password)) || mb_strlen($password) < 5) {
            $this->erroresFormulario['password'] = 'La contraseña debe tener al menos 5 caracteres de longitud.';
        }
    }

    /**
     * Comprueba si las contraseñas coinciden.
     *
     * @param string $newPassword La nueva contraseña.
     * @param string $confirmPassword La confirmación de la nueva contraseña.
     */
    public function comprobarSimilitude($newPassword, $confirmPassword)
    {
        if ($newPassword !== $confirmPassword) {
            $this->erroresFormulario['confirm-password'] = 'Las nuevas contraseñas no coinciden.';
        }
    }
}
?>