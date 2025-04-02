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

        case 'create_order':
            checkForUser($userController);
            
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            try {
                $db->beginTransaction();
                
                // 1. validar que hay producto en el carrito
                if (empty($data['items'])) {
                    throw new Exception('El carrito está vacío');
                }
        
                // 2. agarrar los ids de producto
                $productIds = array_column($data['items'], 'producto_id');
                if (empty($productIds)) {
                    throw new Exception('No hay IDs de producto válidos');
                }
        
                // 3. verificar existencia de productos en tabla
                $placeholders = implode(',', array_fill(0, count($productIds), '?'));
                $stmt = $db->prepare("
                    SELECT id, costo FROM productos 
                    WHERE id IN ($placeholders)
                ");
                $stmt->execute($productIds);
                $validProducts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
                
                // 4. checar que no tenga productos invalidos
                $invalidIds = array_diff($productIds, array_keys($validProducts));
                if (!empty($invalidIds)) {
                    throw new Exception("Productos no válidos: " . implode(',', $invalidIds));
                }
        
                // 5. Crear orden
                $stmt = $db->prepare("
                    INSERT INTO ordenes (id_usuario, total, tipo_entrega, direccion_entrega)
                    VALUES (?, ?, ?, ?)
                ");
                
                // Calculate total from valid products
                $total = 0;
                foreach ($data['items'] as $item) {
                    if (isset($validProducts[$item['producto_id']])) {
                        $total += $validProducts[$item['producto_id']] * $item['cantidad'];
                    }
                }
                
                $stmt->execute([
                    $_SESSION['user_id'],
                    $total,
                    $data['delivery_type'],
                    $data['address'] ?? null
                ]);
                $orderId = $db->lastInsertId();
                
                // 6. Add order items
                $stmt = $db->prepare("
                    INSERT INTO pedido (id_pedido, id_usuario, id_producto, cantidad, precio_unitario)
                    VALUES (?, ?, ?, ?, ?)
                ");
                
                foreach ($data['items'] as $item) {
                    if (isset($validProducts[$item['producto_id']])) {
                        $stmt->execute([
                            $orderId,
                            $_SESSION['user_id'],
                            $item['producto_id'],
                            $item['cantidad'],
                            $validProducts[$item['producto_id']]
                        ]);
                        
                        // Remove from cart
                        $stmt = $db->prepare("DELETE FROM carrito WHERE id = ?");
                        $stmt->execute([$item['cart_id']]);
                    }
                }
                
                $db->commit();
                
                sendJsonResponse([
                    'success' => true,
                    'orderId' => $orderId,
                    'total' => $total
                ]);
                
            } catch (Exception $e) {
                $db->rollBack();
                error_log("Order Error: " . $e->getMessage());
                sendJsonResponse([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;
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