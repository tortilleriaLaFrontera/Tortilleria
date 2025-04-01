<?php
class HomeController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function index() {
        
        // Verificar si el usuario está logueado
        if(isset($_SESSION['user_id'])) {
            // Redirigir al dashboard
            include_once './views/home.php';
        } else {
            // Mostrar página de inicio/login
            $currentPage = 'home';
            include_once './views/home.php';
        }
    }
    
    public function nosotros() {
        $currentPage = 'nosotros';
        include_once './views/templates/header.php';
        include_once './views/nosotros.php';
        include_once './views/templates/footer.php';
    }
    
    public function productos() {
        $currentPage = 'productos';

        //uso de productcontroller para recibir productos
        global $productController;
        $products = $productController->listProducts();

        include_once './views/templates/header.php';
        include_once './views/productos.php';
        include_once './views/templates/footer.php';
    }
    
    public function contacto() {
        $currentPage = 'contacto';
        include_once './views/templates/header.php';
        include_once './views/contacto.php';
        include_once './views/templates/footer.php';
    }
    public function perfil() {
        $currentPage = 'perfil';
        $currentWindow = 'perfil';
        include_once './views/templates/header.php';
        include_once './views/perfil.php';
        include_once './views/templates/footer.php';
    }
    public function checkout() {
        $currentPage = 'perfil';
        $currentWindow = 'checkout';
        include_once './views/templates/header.php';
        include_once './views/perfil.php';
        include_once './views/templates/footer.php';
    }
    public function cartdetails() {
        $currentPage = 'perfil';
        $currentWindow = 'carrito';
        
        

        
        include_once './views/templates/header.php';
        include_once './views/perfil.php';
        include_once './views/templates/footer.php';
    }
    public function showRegisterForm() {
        $currentPage = 'register';//nvo
        include_once './views/templates/header.php';//nvo
        include_once './views/register.php';
        include_once './views/templates/footer.php';//nvo
    }
    public function viewCart($cartItems) {
        // Calculate total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['cantidad'];
        }
        
        // Include your cart view file
        require_once './views/cart.php';
    }
}
?>