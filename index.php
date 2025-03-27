<?php
// Iniciar sesión
session_start();

// Incluir la clase de base de datos
require_once './config/database.php';

// Incluir controladores
require_once './controllers/HomeController.php';
require_once './controllers/UserController.php';

// Crear instancia de la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear instancias de controladores
$homeController = new HomeController($db);
$userController = new UserController($db);

// Obtener la acción de la URL
$action = $_GET['action'] ?? '';

// Enrutamiento básico
switch($action) {
    case 'register':
        $userController->register();
        break;
        
    case 'login':
        $userController->login();
        break;
    case 'logout':
        $userController->logout();
        break;
    
    case 'nosotros':
        $homeController->nosotros();
        break;
        
    case 'productos':
        $homeController->productos();
        break;
        
    case 'contacto':
        $homeController->contacto();
        break;

    default:
        $homeController->index();
        break;
}
?>