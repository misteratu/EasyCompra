<?php

require_once __DIR__ . '/DTO/UserDTO.php';
require_once __DIR__ . '/ServiceLayer/UserService.php';
require_once __DIR__ . '/ServiceLayer/ProductService.php';
require_once __DIR__ . '/ServiceLayer/CategoryService.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/form.php';
require_once __DIR__ . '/utils.php';

class FormAdmin implements Form
{
    private $erroresFormulario = [];

    /**
     * Genera el formulario para mostrar el perfil del usuario.
     *
     * @return string El contenido HTML generado.
     */
    public function generateForm()
    {
        $htmlContent = '<h2>Página de administrador</h2>';
        // Sección para agregar una categoría
        $htmlContent .= '<div class="adminForms">';
        $htmlContent .= '<h3>Agregar una categoría</h3>';
        $htmlContent .= '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" enctype="multipart/form-data">';
        $htmlContent .= '<div class="campoForm">';
        $htmlContent .= '<label for="categoryToAdd">Nombre de la categoría:</label>';
        $htmlContent .= '<input type="text" id="categoryToAdd" name="categoryToAdd" required>';
        $htmlContent .= '</div>';
        $htmlContent .= '<button type="submit" class="botonForm">Agregar categoría</button>';
        $htmlContent .= '</form>';
        $htmlContent .= '</div>';

        // Sección para eliminar una categoría
        $htmlContent .= '<div class="adminForms">';
        $htmlContent .= '<h3>Eliminar una categoría</h3>';
        $htmlContent .= '<form id="deleteCategoryForm" method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
        $htmlContent .= '<label for="categoryToDelete">Seleccione la categoría a eliminar:</label>';
        $htmlContent .= '<select name="categoryToDelete" id="categoryToDelete">';

        // Obtener todas las categorías disponibles
        $categories = CategoryService::getAllCategories();
        foreach ($categories as $category) {
            $htmlContent .= '<option value="' . $category->Id() . '">' . $category->Nombre() . '</option>';
        }

        $htmlContent .= '</select>';
        $htmlContent .= '<br>'; // Añadir espacio entre el menú desplegable y el botón
        $htmlContent .= '<button type="submit" class="botonForm">Eliminar categoría</button>';
        $htmlContent .= '</form>';
        $htmlContent .= '</div>';

        // Sección para eliminar un usuario
        $htmlContent .= '<div class="adminForms">';
        $htmlContent .= '<h3>Eliminar un usuario</h3>';
        $htmlContent .= '<form id="deleteUserForm" method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">';
        $htmlContent .= '<label for="userToDelete">Seleccione el usuario a eliminar:</label>';
        $htmlContent .= '<select name="userToDelete" id="userToDelete">';

        // Obtener todos los usuarios disponibles
        $users = UserService::getAllUsers();
        foreach ($users as $user) {
            $htmlContent .= '<option value="' . $user->Id() . '">' . $user->NombreUsuario() . '</option>';
        }

        $htmlContent .= '</select>';
        $htmlContent .= '<br>'; // Añadir espacio entre el menú desplegable y el botón
        $htmlContent .= '<button type="submit" class="botonForm">Eliminar usuario</button>';
        $htmlContent .= '</form>';
        $htmlContent .= '</div>';

        // Dar cuenta de administrador a un usuario
        $htmlContent .= '<div class="adminForms">';
        $htmlContent .= '<h3>Dar cuenta de administrador a un usuario</h3>';
        $htmlContent .= '<form id="adminUserForm" method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">';
        $htmlContent .= '<label for="userToAdmin">Seleccione el usuario a dar cuenta de administrador:</label>';
        $htmlContent .= '<select name="userToAdmin" id="userToAdmin">';
        foreach ($users as $user) {
            $htmlContent .= '<option value="' . $user->Id() . '">' . $user->NombreUsuario() . '</option>';
        }
        $htmlContent .= '</select>';
        $htmlContent .= '<br>';
        $htmlContent .= '<button type="submit" class="botonForm">Dar cuenta de administrador</button>';
        $htmlContent .= '</form>';
        $htmlContent .= '</div>';

        // Quitar cuenta de administrador a un usuario
        $htmlContent .= '<div class="adminForms">';
        $htmlContent .= '<h3>Quitar cuenta de administrador a un usuario</h3>';
        $htmlContent .= '<form id="removeAdminForm" method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">';
        $htmlContent .= '<label for="userToRemoveAdmin">Seleccione el usuario a quitar cuenta de administrador:</label>';
        $htmlContent .= '<select name="userToRemoveAdmin" id="userToRemoveAdmin">';
        foreach ($users as $user) {
            $htmlContent .= '<option value="' . $user->Id() . '">' . $user->NombreUsuario() . '</option>';
        }
        $htmlContent .= '</select>';
        $htmlContent .= '<br>';
        $htmlContent .= '<button type="submit" class="botonForm">Quitar cuenta de administrador</button>';
        $htmlContent .= '</form>';
        $htmlContent .= '</div>';

        return $htmlContent;
    }

    /**
     * Procesa el formulario.
     */
    public function processForm()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["categoryToDelete"])) {
                $categoryId = $_POST["categoryToDelete"];
                // Eliminar la categoría con el ID especificado
                $verif = CategoryService::deleteCategory($categoryId);
                if ($verif) {
                    return '<p class="success">Categoría eliminada correctamente.</p>';
                } else {
                    return '<p class="error">Error al eliminar la categoría.</p>';
                }
            } elseif (isset($_POST["userToDelete"])) {
                $userId = $_POST["userToDelete"];
                // Eliminar el usuario con el ID especificado
                $verif = UserService::deleteUser($userId);
                if ($verif) {
                    return '<p class="success">Usuario eliminado correctamente.</p>';
                } else {
                    return '<p class="error">Error al eliminar el usuario.</p>';
                }
            } elseif (isset($_POST["categoryToAdd"])) {
                $categoryName = $_POST["categoryToAdd"];
                // Agregar la nueva categoría
                $verif = CategoryService::insertCategory($categoryName);
                if ($verif) {
                    return '<p class="success">Categoría agregada correctamente.</p>';
                } else {
                    return '<p class="error">Error al agregar la categoría.</p>';
                }
            } elseif (isset($_POST["userToAdmin"])) {
                $userId = $_POST["userToAdmin"];
                // Dar cuenta de administrador al usuario con el ID especificado
                $verif = UserService::setuseradmin($userId);
                if ($verif) {
                    return '<p class="success">Cuenta de administrador dada correctamente.</p>';
                } else {
                    return '<p class="error">Error al dar cuenta de administrador.</p>';
                }
            } elseif (isset($_POST["userToRemoveAdmin"])) {
                $userId = $_POST["userToRemoveAdmin"];
                // Quitar cuenta de administrador al usuario con el ID especificado
                $verif = UserService::unsetuseradmin($userId);
                if ($verif) {
                    return '<p class="success">Cuenta de administrador quitada correctamente.</p>';
                } else {
                    return '<p class="error">Error al quitar cuenta de administrador.</p>';
                }
            }
        }
    }
}
?>
