<?php
class UserController {
    private $db;
    private $user;
    
    public function __construct($db) {
        $this->db = $db;
        require_once './models/User.php';
        $this->user = new User($db);
    }
    
    public function register() {
        // Verificar si se envió el formulario
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Validar datos del formulario
            $errors = [];
            
            // Validar nombre de usuario
            if(empty($_POST['username'])) {
                $errors[] = "El nombre de usuario es obligatorio";
            }
            
            // Validar email
            if(empty($_POST['email'])) {
                $errors[] = "El correo es obligatorio";
            } elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "El formato del correo es inválido";
            }
            
            // Validar contraseña
            if(empty($_POST['password'])) {
                $errors[] = "La contraseña es obligatoria";
            } elseif(strlen($_POST['password']) < 6) {
                $errors[] = "La contraseña debe tener al menos 6 caracteres";
            }
            
            // Ingresar dirección
            if(empty($_POST['direccion'])) {
                $errors[] = "la dirección es obligatoria";
            }

            if(empty($_POST['telefono'])) {
                $errors[] = "El telefono es obligatorio";
            } elseif(!ctype_digit($_POST['telefono'])) {
                $errors[] = "El telefono solo debe contener números";
            } elseif(strlen($_POST['telefono']) < 10) {
                $errors[] = "El telefono debe de tener 10 digitos";
            }
            // Si no hay errores, registrar al usuario
            if(empty($errors)) {
                // Asignar valores
                $this->user->username = $_POST['username'];
                $this->user->email = $_POST['email'];
                $this->user->password = $_POST['password'];
                $this->user->direccion = $_POST['direccion'];
                $this->user->telefono = $_POST['telefono'];
                
                // Intentar crear el usuario
                if($this->user->create()) {
                    $_SESSION['message'] = "Usuario registrado correctamente";
                    header("Location: index.php");
                    exit;
                } else {
                    $errors[] = "Error al registrar el usuario";
                }
            }
            
            // Si hay errores, mostrar formulario con errores
            $_SESSION['errors'] = $errors;
            $currentPage = 'register';
            include_once './views/register.php';
        } else {
            // Mostrar formulario de registro
            $currentPage = 'register';
            include_once './views/register.php';
        }
    }
    
    public function login() {
        // Verificar si se envió el formulario
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Validar datos del formulario
            $errors = [];
            
            // Validar email
            if(empty($_POST['email'])) {
                $errors[] = "El correo es obligatorio";
            }
            
            // Validar contraseña
            if(empty($_POST['password'])) {
                $errors[] = "La contraseña es obligatoria";
            }
            
            // Si no hay errores, autenticar al usuario
            if(empty($errors)) {
                // Asignar valores
                $this->user->email = $_POST['email'];
                
                // Verificar si el email existe
                if($this->user->emailExists()) {
                    // Verificar la contraseña
                    if(password_verify($_POST['password'], $this->user->password)) {
                        // Iniciar sesión
                        $_SESSION['user_id'] = $this->user->id;
                        $_SESSION['username'] = $this->user->username;
                         
                        global $cartController;
                        $cartController->setUserId($this->user->id);
                        // Redirigir al dashboard
                        header("Location: index.php");
                        exit;
                    } else {
                        $errors[] = "Contraseña incorrecta";
                    }
                } else {
                    $errors[] = "Usuario no encontrado";
                }
            }
            
            // Si hay errores, mostrar formulario con errores
            $_SESSION['errors'] = $errors;
            $currentPage = 'login';
            include_once './views/login.php';
        } else {
            // Mostrar formulario de login
            $currentPage = 'login';
            include_once './views/login.php';
        }
    }
    
    public function logout() {
        global $cartController;
        $cartController->setUserId(null); // This will set $this->cart to null
        
        // Destroy session
        $_SESSION = [];
        session_destroy();
        
        // Redirect
        header("Location: index.php");
        exit;
    }
}
?>