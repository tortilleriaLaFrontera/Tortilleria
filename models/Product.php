<?php

class Product {
    private $conn;
    private $table_name = "productos";

    public $id;
    public $nombre;
    public $descripcion;
    public $imagen;
    public $stock;

    public function __construct($db) {
        $this->conn = $db;
    }

    //Obtener todos los productos
    public function getAllProducts() {
        $query = "SELECT id, nombre, descripcion, imagen, stock FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //obtener producto por id
    public function getProductById($productId) {
        $query = "SELECT id, nombre, descripcion, imagen, stock FROM " . $this->table_name . " WHERE id = :productId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":productId", $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Verificar si hay stock disponible
    public function hasStock($productId, $cantidad) {
        $query = "SELECT stock FROM " . $this->table_name . " WHERE id = :productId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":productId", $productId, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $product && $product['stock'] >= $cantidad;
    }
    // Reducir stock tras una compra
    public function reduceStock($productId, $cantidad) {
        $query = "UPDATE " . $this->table_name . " SET stock = stock - :cantidad WHERE id = :productId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(":productId", $productId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}