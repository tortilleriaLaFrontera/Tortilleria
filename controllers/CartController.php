<?php
class CartController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    //agregar producto
    public function addToCart($userId, $productId, $cantidad = 1) {
        $product = $this->getProduct($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'Producto no encontrado'];
        }

        // revisar si existe en el carrito
        $existeProducto = $this->db->prepare("SELECT * FROM carrito WHERE user_id = ? AND producto_id = ?");
        $articulo = $existeProducto->fetch();

        if ($articulo) {
            //actualizar cantidad si ya existe en carrito
            $nvoCantidad = $articulo['cantidad'] + $cantidad;
            $stmt = $this->db->prepare("UPDATE carrito SET cantidad = ? WHERE id = ?");
            $stmt->execute([$nvoCantidad, $articulo['id']]);
        } else {
            // Si no existe el articulo en carrito se agrega
            $stmt = $this->db->prepare("INSERT INTO carrito (user_id, producto_id, cantidad) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $productId, $cantidad]);
        }
        return ['success' => true, 'message' => 'Producto añadido al carrito'];
    }

    //remover producto
    public function removerDeCarrito($cartId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM carrito WHERE id = ? AND user_id = ?");
        $stmt->execute([$cartId, $userId]);
        return $stmt->rowCount() > 0;
    }

    // Consultar contenido de carrito
    public function getCart($userId) {
        $stmt = $this->db->prepare("
            SELECT c.id, c.cantidad, p.id as producto_id, p.nombre, p.descripcion, p.precio, p.imagen 
            FROM carrito c 
            JOIN productos p ON c.producto_id = p.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar contenido de carrito
    public function updateCantidad($cartId, $userId, $quantity) {
        if ($quantity <= 0) {
            return $this->removerDeCarrito($cartId, $userId);
        }

        $stmt = $this->db->prepare("UPDATE carrito SET cantidad = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$quantity, $cartId, $userId]);
        return $stmt->rowCount() > 0;
    }

    // Helper function to get product
    private function getProduct($productId) {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }
}