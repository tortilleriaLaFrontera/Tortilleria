<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tortilleria la Frontera</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    
    <!-- Definición de css a usar en base a pagina -->
    <?php
        // Default to index.css if currentPage isn't set
        $page = $currentPage ?? 'index';

        switch($page) {
            case 'nosotros':
                echo '<link rel="stylesheet" href="css/cont-nosotros.css">';
                break;
            case 'productos':
                echo '<link rel="stylesheet" href="css/cont-productos.css">';
                break;
            case 'contacto':
                echo '<link rel="stylesheet" href="css/contacto.css">';
                break;
            case 'login':
                echo '<link rel="stylesheet" href="css/cont-login.css">';
                break;
            case 'register':
                echo '<link rel="stylesheet" href="css/cont-registro.css">';
                break;
            case 'perfil':
                echo '<link rel="stylesheet" href="css/cont-perfil.css">';
                $window = $currentWindow ?? 'perfil';
                switch ($window) 
                {
                    case 'perfil': echo '<link rel="stylesheet" href="css/perfil-userdetails.css">'; break;
                    case 'carrito': echo '<link rel="stylesheet" href="css/perfil-cartdetails.css">'; break;
                    case 'checkout': echo '<link rel="stylesheet" href="css/perfil-checkout.css">'; break;
                }
                echo '<script src="js/perfil.js" defer></script>';
            default: // index and any other pages
                echo '<link rel="stylesheet" href="css/cont-index.css">';
        }
    ?>

    <!-- <link rel="stylesheet" href="./css/style.css"> -->
    <script src="js/bg-slideshow.js" defer></script>
</head>
<body>
    <header>
        <div class="patty menu" onclick="abrirmenu()"><svg xmlns="http://www.w3.org/2000/svg" height="5rem" viewBox="0 -960 960 960" width="48px" fill="#000000"><path d="M120-240v-60h720v60H120Zm0-210v-60h720v60H120Zm0-210v-60h720v60H120Z"/></svg></div>
            <nav class="links">
                <span class="headerSpace header-left">
                    
                    <a class="navegacion <?= ($page === 'productos') ? 'activo' : '' ?>" href="index.php?action=productos">PRODUCTOS</a>
                    <a class="navegacion <?= ($page === 'nosotros') ? 'activo' : '' ?>" href="index.php?action=nosotros">NOSOTROS</a>

                </span>
                
                <span class="headerSpace header-center">

                    <a class="navegacion iniciolink <?= ($currentPage === 'index') ? 'activo' : '' ?>" href="index.php">
                        <img class="logo-img" src="img/logo_Frontera.jpg" alt="La Frontera">
                        <span class="logo-texto">INICIO</span>
                    </a>

                </span>
                
                <span class="headerSpace header-right">

                    <a class="navegacion <?= ($page === 'contacto') ? 'activo' : '' ?>" href="index.php?action=contacto">CONTACTO</a>
                    
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a class="navegacion" href="index.php?action=login">INICIAR SESION</a>
                    <?php else: ?>
                        <div class="navegacion header-dropdowns">
                            
                            <!-- Contenedor carrito -->
                            <div class="dropdown cart-dropdown">
                                <a class="navegacion cart-toggle" href="javascript:void(0)" data-cart-link="index.php?action=cart">
                                    <span class="cart-icon-wrapper">
                                        <img class="userBtns" src="img/cart.png" alt="cart">
                                        <span class="cart-count">
                                            <?php 
                                                if(isset($cartController)) {
                                                    echo $cartController->getCartCount($_SESSION['user_id']);
                                                } else {
                                                    echo '0';
                                                }
                                            ?>
                                        </span>
                                    </span>
                                </a>    
                                
                                <div class="dropdown-content cart-content">
                                    <?php 
                                    // Seccion para visualizar el carrito cuando haces hover (se suponsio)
                                    if(isset($_SESSION['user_id'])) {
                                        // $cartItems = 
                                        $isDropdown = true;
                                        include './views/cart.php';
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <!-- User Profile Dropdown -->
                            <div class="dropdown profile-dropdown">
                                <a class="profile-toggle" href="javascript:void(0)">
                                    <img class="userBtns" src="img/profile-picture.png" style="border-radius: 50%;" alt="profile">
                                </a>
                                <div class="dropdown-content profile-content">
                                    <a href="index.php?action=perfil">Mi Perfil</a>
                                    <a href="index.php?action=logout">Cerrar Sesión</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                </span>
                
            </nav>
            <a href="index.html" class="patty"><img src="img/logo_Frontera.jpg" alt="La Frontera"></a>
        <span class="patty usuario"></span>
    </header>
    <main class="container">