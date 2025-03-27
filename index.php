<?php
// Iniciar sesión
session_start();

// Incluir la clase de base de datos
require_once './config/database.php';

// Incluir controladores
require_once './controllers/HomeController.php';
require_once './controllers/UserController.php';
require_once './controllers/CartController.php';

// Crear instancia de la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear instancias de controladores
$homeController = new HomeController($db);
$userController = new UserController($db);
$cartController = new CartController($db);

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
    case 'add_to_cart':
        if (!isset($_SESSION['user_id'])) {
            $userController->login();
        }
        $productId = $_POST['producto_id'] ?? 0;
        $cantidad = $_POST['cantidad'] ?? 1;
        $result = $cartController->addToCart($_SESSION['user_id'], $productId, $cantidad);
        header("Location: index.php?action=productos&cart_success=1");
        exit();
    case 'view_cart':
        if (!isset($_SESSION['user_id'])) {
            $userController->login();
        }
        $cartItems = $cartController->getCart($_SESSION['user_id']);
        // You'll need to create a method in HomeController to display the cart
        $homeController->viewCart($cartItems);
        break;

    case 'update_cart':
        if (!isset($_SESSION['user_id'])) {
            $userController->login();
        }
        $cartId = $_POST['cart_id'] ?? 0;
        $cantidad = $_POST['quantity'] ?? 1;
        $cartController->updateCantidad($cartId, $_SESSION['user_id'], $cantidad);
        header("Location: index.php?action=view_cart");
        exit();
        

    case 'remove_from_cart':
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $cartId = $_POST['cart_id'] ?? 0;
        $cartController->removerDeCarrito($cartId, $_SESSION['user_id']);
        header("Location: index.php?action=view_cart");
        exit();
        
    
    default:
        $homeController->index();
        break;
}
?>