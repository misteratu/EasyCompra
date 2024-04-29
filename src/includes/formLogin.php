<?php

require_once __DIR__ . '/ServiceLayer/UserService.php';
require_once __DIR__ . '/DTO/UserDTO.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/form.php';
require_once __DIR__ . '/utils.php';

/**
 * Clase para generar y procesar un formulario de inicio de sesión.
 */
class FormLogin implements Form
{
    private $erroresFormulario = [];    // En la siguiente practica se incluira en la superclase
    private $username;
    private $email;
    private $password;
    /**
     * Genera el formulario HTML de inicio de sesión.
     * 
     * @return string El código HTML del formulario de inicio de sesión.
     */
    public function generateForm()
    {
        $erroresGlobalesFormulario = generaErroresGlobalesFormulario($this->erroresFormulario);
        $erroresCampos = generaErroresCampos(['nombreUsuario', 'email', 'password'], $this->erroresFormulario);
        $formHTML = '
    <div class="formulario">
        <h2>LOG IN</h2>
        '. $erroresGlobalesFormulario .'
        <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
            <div class="campoForm">
                <label for="username">Introduce nombre de usuario :</label>
                <input type="text" id="username" name="username" value="'. $this->username .'">
                <p class="errorForm">'.$erroresCampos['nombreUsuario'].'</p>
            </div>
            <div class="campoForm">
                <label for="email">Introduce correo electrónico :</label>
                <input type="email" id="email" name="email" value="'. $this->email .'">
                <p class="errorForm">'.$erroresCampos['email'].'</p>
            </div>
            <div class="campoForm">
                <label for="password">Introduce contraseña:</label>
                <input type="password" id="password" name="password" value="'. $this->password .'">
                <p class="errorForm">'.$erroresCampos['password'].'</p>
            </div>
            <button class="botonForm" type="submit">Boton de log in</button>
        </form>
    </div>';

        return $formHTML; // Devuelve el código HTML del formulario
    }

    /**
     * Procesa los datos enviados por el formulario y devuelve el contenido HTML generado.
     * 
     * @return string El contenido HTML generado a partir de los resultados del intento de inicio de sesión.
     */
    public function processForm()
    {
        // Verifica si se ha enviado un formulario mediante el método POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obtiene los datos del formulario
            $this->username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);;
            $this->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $this->password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            //Comprobar datos
            $this->comprobarNombreUsuario($this->username);
            $this->comprobarEmail($this->email);
            $this->comprobarPassword($this->password);
            

            if(count($this->erroresFormulario) === 0){
                //Obtener usuario
                $user = UserService::login($this->username, $this->email, $this->password);

                if (!$user) {
                    $this->erroresFormulario[] = 'Nombre de usuario o contraseña incorrectos';
                } else {
                    $_SESSION['login'] = true;
                    $_SESSION['email'] = $user->Correo();
                    $_SESSION['nombre'] = $user->NombreUsuario();
                    $_SESSION['esAdmin'] = $user->Administrador();
                    header('Location: index.php');
                    exit();
                }
            }
        }
    }

    private function comprobarNombreUsuario($nombreUsuario){
        if ( ! $nombreUsuario || empty($nombreUsuario=trim($nombreUsuario))) {
            $this->erroresFormulario['nombreUsuario'] = 'El nombre de usuario debe estar relleno';
        }
    }

    private function comprobarEmail($email){
        if ( ! $email || empty($email=trim($email))) {
            $this->erroresFormulario['email'] = 'El email es un campo obligatorio';
        }
    }

    private function comprobarPassword($password){
        if ( ! $password || empty($password=trim($password))) {
            $this->erroresFormulario['password'] = 'El password es un campo obligatorio';
        }
    }
}
