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
        // Navegar a pagina registro o enviar form
        $userController->register();
        break;
        
    case 'login':
        // Navegar a pagina login o enviar form
        $userController->login();
        break;
    case 'logout':
        // Cerrar sesion
        $userController->logout();
        break;
    
    case 'nosotros':
        // Navegar a pagina nosotros
        $homeController->nosotros();
        break;
        
    case 'productos':
        // Navegar a pagina productos
        $homeController->productos();
        break;
        
    case 'contacto':
        // Navegar a pagina contacto
        $homeController->contacto();
        break;
    
    
    case 'cart':
    case 'view_cart': // Keeping both for backward compatibility
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $cartItems = $cartController->getCart($_SESSION['user_id']);
        $homeController->viewCart($cartItems);
        break;
    case 'update_cart':
        // actualizar el carrito
        if (!isset($_SESSION['user_id'])) {
            $userController->login();
        }
        $cartId = $_POST['cart_id'] ?? 0;
        $cantidad = $_POST['quantity'] ?? 1;
        $cartController->updateCantidad($cartId, $_SESSION['user_id'], $cantidad);
        header("Location: index.php?action=view_cart");
        exit();
        

    case 'remove_from_cart':
        // sacar producto de carrito
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $cartId = $_POST['cart_id'] ?? 0;
        $cartController->removerDeCarrito($cartId, $_SESSION['user_id']);
        header("Location: index.php?action=view_cart");
        exit();
        
    case 'cart_dropdown':
        // dropdown de productos en carrito
        $isDropdown = true;
        $cartItems = $cartController->getCart($_SESSION['user_id']);
        include './views/cart.php';
        exit();
        break;
    case 'add_to_cart':
        // Agrega producto a carrito
        header('Content-Type: application/json'); 
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
            exit();
        }
        
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Producto inválido']);
            exit();
        }
        
        try {
            $result = $cartController->addToCart($_SESSION['user_id'], $productId, $quantity);
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error del servidor']);
        }
        exit();
    default:
        // lleva al index
        $homeController->index();
        break;
}
?>