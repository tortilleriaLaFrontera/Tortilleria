<?php

class Product {
    private $conn;
    private $table_name = "productos";

    public $id;
    public $nombre;
    public $descripcion;
    public $imagen;
    public $costo;
    public $stock;

    public function __construct($db) {
        $this->conn = $db;
    }

    //Obtener todos los productos
    public function getAllProducts() {
        $query = "SELECT id, nombre, descripcion, imagen, costo, stock FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //obtener producto por id
    public function getProductById($productId) {
        $query = "SELECT id, nombre, descripcion, imagen, costo, stock FROM " . $this->table_name . " WHERE id = :productId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":productId", $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}