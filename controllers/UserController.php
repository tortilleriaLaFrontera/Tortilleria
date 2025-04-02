<?php
class UserController {
    private $db;
    public $user;
    
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
                        $_SESSION['email'] = $this->user->email;
                        $_SESSION['telefono'] = $this->user->telefono;
                        $_SESSION['direccion'] = $this->user->direccion;
                         
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

    public function getSessionInfo($idUsr) {
        $userData = $this->user->getUsr($idUsr);
        return [
            'success' => !empty($userData),
            'data' => $userData ?: null,
            'message' => empty($userData) ? 'No se encontro el usuario' : ''
        ];
    }
    public function updateUserInfo($userId, $userData) {
        try {
            // Validate current password if changing password
            if (!empty($userData['new_password'])) {
                if (empty($userData['current_password'])) {
                    return ['success' => false, 'message' => 'Debe ingresar la contraseña actual'];
                }
                
                $user = $this->getUserById($userId);
                if (!password_verify($userData['current_password'], $user['password'])) {
                    return ['success' => false, 'message' => 'Contraseña actual incorrecta'];
                }
                
                if ($userData['new_password'] !== $userData['confirm_password']) {
                    return ['success' => false, 'message' => 'Las contraseñas no coinciden'];
                }
            }
            
            // Build update query
            $updates = [];
            $params = [];
            
            if (!empty($userData['username'])) {
                $updates[] = 'username = ?';
                $params[] = $userData['username'];
            }
            
            if (!empty($userData['direccion'])) {
                $updates[] = 'direccion = ?';
                $params[] = $userData['direccion'];
            }
            
            if (!empty($userData['telefono'])) {
                $updates[] = 'telefono = ?';
                $params[] = $userData['telefono'];
            }
            
            if (!empty($userData['new_password'])) {
                $updates[] = 'password = ?';
                $params[] = password_hash($userData['new_password'], PASSWORD_DEFAULT);
            }
            
            if (empty($updates)) {
                return ['success' => false, 'message' => 'No se proporcionaron datos para actualizar'];
            }
            
            $params[] = $userId; // For WHERE clause
            
            $stmt = $this->db->prepare("
                UPDATE users 
                SET " . implode(', ', $updates) . " 
                WHERE id = ?
            ");
            $stmt->execute($params);
            
            // Return updated user data
            return [
                'success' => true,
                'message' => 'Información actualizada correctamente',
                'user' => $this->getUserById($userId)
            ];
            
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al actualizar la información'];
        }
    }
    private function getUserById($userId) {
        $stmt = $this->db->prepare("
            SELECT id, username, email, direccion, telefono, password 
            FROM users 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>