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
    case 'userInfo':
        $usrData = $userController->getSessionInfo($_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode($usrData);
        exit();
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
        // Navegar a pagina perfil - ver estado de orden
        $homeController->checkout();
        break;
    case 'cartdetails':
        // Navegar a pagina perfil - cart details y creacion de orden
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
    // solicita JSON de carrito sin crear view
    case 'cart':
        checkForUser($userController);
        $cartData = $cartController->getCart(true); // ['success','items','count(por ahora)']
        
        // Directly return JSON without output buffering
        header('Content-Type: application/json');
        echo json_encode($cartData);
        exit();
    case 'view_cart':  // endpoint para carrito en perfil
        checkForUser($userController);
        $cartData = $cartController->getCart(true);
        
        if ($cartData['success']) {
            sendJsonResponse([
                'success' => true,
                'items' => $cartData['items'],
                'count' => $cartData['count'],
                'total' => $cartController->getCartTotal()
            ]);
        } else {
            sendJsonResponse(['success' => false, 'message' => 'Error loading cart']);
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
    case 'cart_decrease':
    case 'cart_increase':
        checkForUser($userController);
        
        // Validate input
        $cartItemId = $_POST['cart_id'] ?? null;
        if (!is_numeric($cartItemId) || $cartItemId <= 0) {
            sendJsonResponse([
                'success' => false,
                'message' => 'ID de artículo inválido'
            ]);
            exit();
        }
    
        // Determine offset (-1 for decrease, 1 for increase)
        $offset = ($action === 'cart_increase') ? 1 : -1;
        
        // Update quantity
        $result = $cartController->updateCantidad($cartItemId, $offset);
        
        // If successful, get updated cart totals
        if ($result['success']) {
            $result['total'] = $cartController->getCartTotal();
            $result['count'] = $cartController->getCartCount($userId);
        }
        
        sendJsonResponse($result);
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
        
        exit();
    case 'view_cart_full':
        checkForUser($userController);
        $cartData = $cartController->getCartFull();  
        
        sendJsonResponse([
            'success' => $cartData['success'],
            'items' => $cartData['items'],
            'count' => $cartData['count'],
            'costoTotal' => $cartData['costoTotal']
        ]);
        break;
    case 'create_order':
        checkForUser($userController);
        
        try {
            // Get JSON data from frontend
            $rawData = file_get_contents('php://input');
            $orderData = json_decode($rawData, true);
            
            // Basic validation
            if (empty($orderData['items']) || !is_numeric($orderData['id_usuario'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Datos del pedido inválidos'
                ]);
                exit();
            }
            
            // Process order through CartController
            $result = $cartController->cart_order($orderData);
            
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
            
        } catch (Exception $e) {
            error_log("Order creation error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
            exit();
        }
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