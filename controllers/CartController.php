<?php

class CartController {
    private $db;
    private $cart;
    public function __construct($db, $userId) {
        $this->db = $db;
        require_once './models/Cart.php';

        //Check de existencia de usuario
        if ($userId) {
            $this->cart = new Cart($db, $userId);
        } else {
            $this->cart = null;
        }
        
    }
    public function setUserId($userId) {
        require_once './models/Cart.php';
        
        if ($userId) {
            $this->cart = new Cart($this->db, $userId);
        } else {
            $this->cart = null;
        }
    }

    //agregar producto
    public function addToCart($productId, $quantity = 1) {
        try {
            $success = $this->cart->addItem($productId, $quantity);
            return [
                'success' => $success,
                'count' => $this->cart->getTotalItems(),
                'message' => $success ? 'Producto agregado' : 'No se pudo agregar el producto'
            ];
        } catch (PDOException $e) {
            error_log("Cart Error: " . $e->getMessage()); // Log for debugging
            return [
                'success' => false,
                'message' => 'Database error',
                'error' => $e->getMessage() // Only in dev environment
            ];
        }
    }

    //remover producto
    public function removeFromCart($itemId) {
        try {
            if (!is_numeric($itemId) || $itemId <= 0) {
                throw new InvalidArgumentException("Invalid cart item ID");
            }
    
            $success = $this->cart->removeItem($itemId);
            
            return [
                'success' => $success,
                'count' => $this->cart->getTotalItems(),
                'message' => $success ? 'Item removed' : 'Failed to remove item'
            ];
        } catch (Exception $e) {
            error_log("Controller Remove Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database error',
                'count' => $this->cart->getTotalItems()
            ];
        }
    }

    //vaciar productos
    public function cleanCart() {
        try {
            $success = $this->cart->removeAllItems();
            return [
                'success' => $success,
                'count' => $this->cart->getTotalItems(),
                'message' => $success ? 'Carrito vaciado' : 'No se pudo vaciar los productos'
            ];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error en base de datos'];
        }
    }
    
    public function getCart($returnFullResponse = false) {
        if (!$this->cart) {
            return $returnFullResponse ? ['success' => false, 'message' => 'User not logged in'] : [];
        }
        
        $response = [
            'success' => true,
            'items' => $this->cart->getItems(),
            'count' => $this->cart->getTotalItems()
        ];
        
        return $returnFullResponse ? $response : $response['items'];
    }

    public function getCartCount($userId) {
        $stmt = $this->db->prepare("
            SELECT SUM(cantidad) as total_items 
            FROM carrito 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_items'] ?? 0; // 0 si esta vacio
    }

    // Ver total (Por implementar)
    public function getCartTotal() {
        if (!$this->cart) return 0;
        $items = $this->cart->getItems();
        $total = 0;
        foreach ($items as $item) {
            // Assuming you have price in your items
            $total += ($item['costo'] ?? 0) * ($item['cantidad'] ?? 1);
        }
        return $total;
    }
    // Actualizar contenido de carrito
    public function updateCantidad($cartItemId, $newQuantity) {
        try {
            $success = $this->cart->updateQuantity($cartItemId, $newQuantity);
            return [
                'success' => $success,
                'count' => $this->cart->getTotalItems(),
                'message' => $success ? 'Cantidad actualizada' : 'No se pudo actualizar la cantidad'
            ];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error en base de datos'];
        }
    }

    // Helper function to get product
    private function getProduct($productId) {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }
}