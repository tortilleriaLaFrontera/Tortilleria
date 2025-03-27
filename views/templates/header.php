<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Login MVC</title>
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
            <a class="navegacion <?= ($currentPage === 'productos') ? 'activo' : '' ?>" href="index.php?action=productos">PRODUCTOS</a>
            <a class="navegacion <?= ($currentPage === 'nosotros') ? 'activo' : '' ?>" href="index.php?action=nosotros">NOSOTROS</a>
            
            <a class="navegacion iniciolink <?= ($currentPage === 'index') ? 'activo' : '' ?>" href="index.php">
                <img class="logo-img" src="img/logo_Frontera.jpg" alt="La Frontera">
                <span class="logo-texto">INICIO</span>
            </a>

            <a class="navegacion <?= ($currentPage === 'contacto') ? 'activo' : '' ?>" href="index.php?action=contacto">CONTACTO</a>
            
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a class="navegacion" href="index.php?action=login">INICIAR SESION</a>
            <?php else: ?>
                <a class="navegacion" href="index.php?action=logout">CERRAR SESION</a>
            <?php endif; ?>
        </nav>
            <a href="index.html" class="patty"><img src="img/logo_Frontera.jpg" alt="La Frontera"></a>
            <span class="patty usuario"></span>
        <!-- <div class="container">
            <h1>Sistema de Login MVC</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="index.php?action=logout">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="index.php?action=register">Registrarse</a></li>
                        <li><a href="index.php">Iniciar Sesión</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div> -->
    </header>
    <main class="container">