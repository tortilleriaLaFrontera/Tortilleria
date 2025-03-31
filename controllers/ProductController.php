<?php
require_once './models/Product.php';

class ProductController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function listProducts() {
        $productModel = new Product($this->db);
        return $productModel->getAllProducts();
    }
}
?>