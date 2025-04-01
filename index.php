<?php
// Iniciar sesión
session_start();

// Incluir la base de datos
require_once './config/database.php';

// Incluir controladores
require_once './controllers/HomeController.php';
require_once './controllers/UserController.php';
require_once './controllers/CartController.php';
require_once './controllers/ProductController.php';


// base de datos
$database = new Database();
$db = $database->getConnection();

//instancia variable de usuario para cartController
$userId = $_SESSION['user_id'] ?? null;
// Crear instancias de controladores
$homeController = new HomeController($db);
$userController = new UserController($db);
$cartController = new CartController($db, $userId);
$productController = new ProductController($db);

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
    
    case 'perfil':
        // Navegar a pagina perfil - datos de usuario
        $homeController->perfil();
        break;
    case 'checkout':
        // Navegar a pagina perfil - orden
        $homeController->checkout();
        break;
    case 'cartdetails':
        // Navegar a pagina perfil - orden
        $homeController->cartdetails();
        break;
    case 'cart_view':
        checkForUser($userController);
        $cartData = $cartController->getCart(true);
        
        if ($cartData['success']) {
            $cartItems = $cartData['items'];
            
            // Handle AJAX requests (dropdown)
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                ob_start();
                include './views/cart.php';
                sendJsonResponse([
                    'success' => true,
                    'cartHtml' => ob_get_clean(),
                    
                ]);
            }
            // Full page render (non-AJAX)
            else {
                include_once './views/templates/header.php';
                include_once './views/cart.php';
                include_once './views/templates/footer.php';
            }
        } else {
            $homeController->index();
        }
        break;
    case 'cart': 
        checkForUser($userController);
        $cartItems = $cartController->getCart()['items'];

        // Check if it's an AJAX request and return JSON if needed
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            ob_start();
            include './views/cart.php';
            sendJsonResponse(['cartHtml' => ob_get_clean()]);
        }

        include './views/templates/header.php';
        include './views/cart.php';
        include './views/templates/footer.php';
        exit();
    case 'view_cart': // Utiliza ambos para compatibilidad
        checkForUser($userController);
        $cartData = $cartController->getCart(true);
        if ($cartData['success']) {
            $cartItems = $cartData['items'];
            include_once './views/templates/header.php';
            include_once './views/cart.php'; // Full view
            include_once './views/templates/footer.php';
        } else {
            $homeController->index();
        }
        break;

    case 'cart_update':
    
        checkForUser($userController);
        
        // actualizar el carrito
        $cartItemId = $_POST['cart_id'] ?? 0;
        $cantidad = $_POST['quantity'] ?? 1;
        $cartController->updateCantidad($cartItemId, $cantidad);

        // Respuesta AJAX
        $cartItems = $cartController->getCart()['items'];
        ob_start();
        include './view/cart.php';
        sendJsonResponse(['cartHtml' => ob_get_clean()]);
        exit();
        

    case 'cart_remove':
        checkForUser($userController);
        
        $cartItemId = (int)($_POST['cart_id'] ?? 0);
        $response = $cartController->removeFromCart($cartItemId);
        
        if ($response['success']) {
            ob_start();
            include './views/cart.php';
            $response['cartHtml'] = ob_get_clean();
        }
        
        sendJsonResponse($response);
        exit();
    
    case 'cart_clean':
        checkForUser($userController);

        // vaciar productos de carrito
        $cartController->cleanCart();

        //respuesta con carrito actualizado para AJAX
        $cartItems = $cartController->getCart()['items'];
        ob_start();
        include './views/cart.php';
        sendJsonResponse(['cartHtml' => ob_get_clean()]);
        exit();

    case 'cart_dropdown':
        checkForUser($userController);
        
        // dropdown de productos en carrito
        $cartItems = $cartController->getCart()['items'];
        $isDropdown = true;
        
        ob_start();
        include './views/cart.php';
        sendJsonResponse(['cartHtml' => ob_get_clean()]);
        exit();
    case 'cart_add':
        checkForUser($userController);
        
        // Agrega producto a carrito
        $productId = (int)($_POST['product_id'] ?? 0);
        $response = $cartController->addToCart($productId);

        
        if ($response['success']) {
            ob_start();
            include './views/cart.php';
            $response['cartHtml'] = ob_get_clean();
        }
        
        sendJsonResponse($response);
        // // respuesta con carrito actualizado para AJAX
        // $cartItems = $cartController->getCart()['items'];
        // ob_start();
        // include './views/cart.php';
        // sendJsonResponse([
        //     'success' => $response['success'],
        //     'cartHtml' => ob_get_clean()
        // ]);
        exit();
    default:
        // lleva al index
        $homeController->index();
        break;
}

// Funciones auxiliares
//Determina si hay usuario activo
function checkForUser($userController) {
    if (!isset($_SESSION['user_id'])) {
        $userController->login();
        exit();
    }
}
//Maneja estandarizado de respuestas AJAX
function sendJsonResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}
?>