<?php

require_once __DIR__ . '/ServiceLayer/UserService.php';
require_once __DIR__ . '/DTO/UserDTO.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/form.php';
require_once __DIR__ . '/utils.php';

/**
 * Clase para generar y procesar un formulario de registro de usuario.
 */
class FormRegistro implements Form
{

    private $erroresFormulario = [];        // En la siguiente practica se incluira en la superclase
    private $nombreUsuario;
    private $email;
    private $password;
    private $password2;
    private $descripcion;
    private $administrador;
    private $file;

    public function generateForm(){
        $erroresGlobalesFormulario = generaErroresGlobalesFormulario($this->erroresFormulario);
        $erroresCampos = generaErroresCampos(['nombreUsuario', 'email', 'password', 'password2', 'foto'], $this->erroresFormulario);
        $action = htmlspecialchars($_SERVER["PHP_SELF"]);
        $contenidoPrincipal = <<<EOS
        <div class="formulario">
            <h2>REGISTRO DE USUARIO</h2>
            $erroresGlobalesFormulario
            <form method="post" action="$action" enctype="multipart/form-data">
                <div class="campoForm">
                    <label for="nombreUsuario">Nombre de usuario:</label>
                    <input type="text" id="nombreUsuario" name="nombreUsuario" value="$this->nombreUsuario">
                    <p class="errorForm">{$erroresCampos['nombreUsuario']}</p>
                </div>
                <div class="campoForm">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" name="email" value="$this->email">
                    <p class="errorForm">{$erroresCampos['email']}</p>
                </div>
                <div class="campoForm">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" value="$this->password">
                    <p class="errorForm">{$erroresCampos['password']}</p>
                </div>
                <div class="campoForm">
                    <label for="confirmPassword">Confirmar contraseña:</label>
                    <input type="password" id="confirmPassword" name="password2" value="$this->password2">
                    <p class="errorForm">{$erroresCampos['password2']}</p>
                </div>
                <div class="campoForm">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" value="$this->descripcion" "></textarea>
                </div>
                <div class="campoForm">
                    <label for="foto">Foto:</label>
                    <input type="file" id="foto" name="foto">
                    <p class="errorForm">{$erroresCampos['foto']}</p>
                </div>
                <div class="campoForm">
                    <label for="administrador">Administrador:</label>
                    <input id="checkBox" type="checkbox" id="administrador" name="administrador"> Administrador
                </div>
                <button class="botonForm" type="submit">Registrarse</button>
            </form>
        </div>
        EOS;

        return $contenidoPrincipal;
    }

    /**
     * Procesa los datos enviados por el formulario y devuelve el contenido HTML generado.
     * 
     * @return string El contenido HTML generado a partir de los resultados del intento de registro de usuario.
     */

    public function processForm()
    {
        // Verifica si se ha enviado un formulario mediante el método POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obtiene los datos del formulario
            $this->nombreUsuario = filter_input(INPUT_POST, 'nombreUsuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $this->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $this->password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $this->password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $this->descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $this->administrador = isset($_POST["administrador"]) ? 1 : 0;

            //Comprobamos datos
            $this->comprobarNombreUsuario($this->nombreUsuario);
            $this->comprobarEmail($this->email);
            $this->comprobarPassword($this->password);
            $this->comprobarPassword2($this->password,$this->password2);
            $this->comprobarFoto();

            // Verifica si las contraseñas coinciden
            if (count($this->erroresFormulario) === 0){
                $user = UserService::register($this->nombreUsuario, $this->email, $this->password, $this->descripcion, $this->administrador, $this->file);
                
                if(!$user){
                    $this->erroresFormulario[] = 'El usuario ya existe';
                }else{
                    $_SESSION['login'] = true;
                    $_SESSION['email'] = $this->email;
                    $_SESSION['nombre'] = $this->nombreUsuario;
                    $_SESSION['esAdmin'] = $this->administrador;
                    header('Location: index.php');
                    exit();
                }
            }
        }
    }

    private function comprobarNombreUsuario($nombreUsuario){
        if ( ! $nombreUsuario || empty($nombreUsuario=trim($nombreUsuario)) || mb_strlen($nombreUsuario) < 3) {
            $this->erroresFormulario['nombreUsuario'] = 'El nombre de usuario tiene que tener una longitud de al menos 3 caracteres.';
        }
    }

    private function comprobarEmail($email){
        if ( ! $email || empty($email=trim($email)) || mb_strlen($email) < 5) {
            $this->erroresFormulario['email'] = 'El email es un campo obligatorio';
        }
    }

    private function comprobarPassword($password){
        if ( ! $password || empty($password=trim($password)) || mb_strlen($password) < 5 ) {
            $this->erroresFormulario['password'] = 'El password tiene que tener una longitud de al menos 5 caracteres.';
        }
    }

    private function comprobarPassword2($password,$password2){
        if ( ! $password2 || empty($password2=trim($password2)) || $password != $password2 ) {
            $this->erroresFormulario['password2'] = 'Los passwords deben coincidir';
        }
    }

    private function comprobarFoto(){
        if ($_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            // Verificar tipo de archivo
            $mime = mime_content_type($_FILES['foto']['tmp_name']);
            $allowedTypes = array('image/jpeg');
            if (in_array($mime, $allowedTypes)) {
               $this->file = $_FILES['foto'];
            } else {
                $this->erroresFormulario['foto'] = 'Tipo de archivo no permitido';
            }
        } else {
            $this->erroresFormulario['foto'] = 'No se ha subido ninguna imagen';
        }
    }
}
