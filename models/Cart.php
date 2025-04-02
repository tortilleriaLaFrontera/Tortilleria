<?php

class Cart {
    private $conn;
    private $table_name = "carrito";
    public $userId;

    // Constructor asocia carrito a usuario
    public function __construct($db, $userId) {
        $this->conn = $db;
        $this->userId = $userId;
    }

    //agregar a carrito
    public function addItem($productId, $quantity = 1) {
        try {
            // 1. Verificar si el producto existe
            $check = $this->conn->prepare("SELECT 1 FROM productos WHERE id = ? LIMIT 1");
            if (!$check->execute([$productId]) || !$check->fetch()) {
                error_log("Product $productId doesn't exist");
                return false;
            }
    
            // 2. Insert/update de producto en carrito
            $stmt = $this->conn->prepare("
                INSERT INTO carrito (user_id, producto_id, cantidad)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE cantidad = cantidad + ?
            ");
            
            return $stmt->execute([ $this->userId, $productId, $quantity, $quantity ]);
            
        } catch (PDOException $e) {
            error_log("Cart Error: ".$e->getMessage());
            return false;
        }
    }
    //eliminar de carrito
    public function removeItem($cartItemId) {
        // Validate input
        if (!is_numeric($cartItemId) || $cartItemId <= 0) {
            error_log("Invalid cart item ID: $cartItemId");
            return false;
        }
    
        try {
            $stmt = $this->conn->prepare("
                DELETE FROM carrito 
                WHERE id = ? AND user_id = ?
            ");
            return $stmt->execute([$cartItemId, $this->userId]);
        } catch (PDOException $e) {
            error_log("Remove Item Error: " . $e->getMessage());
            return false;
        }
    }

    //vaciar carrito
    public function removeAllItems() {
        $stmt = $this->conn->prepare("
            DELETE FROM carrito
            WHERE user_id = ?
        ");
        //regresa true si se logro la operacion en base de datos
        return $stmt->execute([$_SESSION['user_id']]);
    }
    //Recibir todos los articulos del carrito con detalles
    public function getItems() {
        $stmt = $this->conn->prepare("
            SELECT c.id, p.nombre, p.descripcion, p.imagen, p.costo, c.cantidad
            FROM carrito c
            JOIN productos p ON c.producto_id = p.id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$this->userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Actualizar cantidad en carrito
    public function updateQuantity($cartItemId, $offsetQuantity) {
        // 1. Actualiza la cantidad del producto en carrito
        $stmt = $this->conn->prepare("
            UPDATE carrito
            SET cantidad = cantidad + ?
            WHERE id = ? AND user_id = ?
        ");
        $success = $stmt->execute([$offsetQuantity, $cartItemId, $this->userId]);
        
        if (!$success) {
            return false;
        }
    
        // si la cantidad nueva es menor o igual a 0, la elimina
        $stmt = $this->conn->prepare("
            DELETE FROM carrito
            WHERE id = ? AND user_id = ? AND cantidad <= 0
        ");
        $stmt->execute([$cartItemId, $this->userId]);
        
        return true;
    }
    public function getFullCartItems() {
        $stmt = $this->conn->prepare("
            SELECT 
                c.id,
                c.user_id,
                c.producto_id,
                c.cantidad,
                p.nombre,
                p.costo,
                p.imagen
            FROM carrito c
            JOIN productos p ON c.producto_id = p.id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$this->userId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //planning to remove
    // Contar productos
    public function getTotalItems() {
        $stmt = $this->conn->prepare("
            SELECT SUM(cantidad) as total
            FROM carrito
            WHERE user_id = ?
        ");
        $stmt->execute([$this->userId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}