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
            // 1. Verify product exists
            $check = $this->conn->prepare("SELECT 1 FROM productos WHERE id = ? LIMIT 1");
            if (!$check->execute([$productId]) || !$check->fetch()) {
                error_log("Product $productId doesn't exist");
                return false;
            }
    
            // 2. Insert/update cart
            $stmt = $this->conn->prepare("
                INSERT INTO carrito (user_id, producto_id, cantidad)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE cantidad = cantidad + ?
            ");
            
            return $stmt->execute([
                $this->userId,    // user_id
                $productId,        // producto_id (corrected)
                $quantity,         // initial cantidad
                $quantity          // cantidad increment
            ]);
            
        } catch (PDOException $e) {
            error_log("Cart Error: ".$e->getMessage());
            return false;
        }
    }
    //eliminar de carrito
    public function removeItem($cartItemId) {
        $stmt = $this->conn->prepare("
            DELETE FROM carrito
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$cartItemId, $this->userId]);
    }

    //vaciar carrito
    public function removeAllItems() {
        $stmt = $this->conn->prepare("
            DELET FROM carrito
            WHERE user_id = ?
        ");
        return $stmt->execute([$this->userId]);
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
    // Actualizar cantidad en carrito
    public function updateQuantity($cartItemId, $newQuantity) {
        if ($newQuantity <= 0) {
            return $this->removeItem($cartItemId);
        }

        $stmt = $this->conn->prepare("
            UPDATE carrito
            SET cantidad = ?
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$newQuantity, $cartItemId, $this->userId]);
    }
}