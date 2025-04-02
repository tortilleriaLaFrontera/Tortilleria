<?php include_once './views/templates/header.php'; ?>
<div class="perfil-main">
    <div class="perfil-left">
        <div class="perfil-left-head">
            <h2><?php echo $currentWindow ?></h2>
        </div>
        <div class="perfil-left-body">
            <div class="left-body-content">
                <?php
                $view;
                switch($currentWindow){
                    case 'perfil':
                        $view = 'datosUser.php';
                        break;
                    case 'carrito':
                        $view = 'canasta.php';
                        break;
                    case 'checkout':
                        $view = 'checkout.php';
                        break;
                    default:
                        $view = 'datosUser.php';
                        break;
                }
                
                $viewFile = __DIR__ . "/perfil-views/{$view}";
                    if (file_exists($viewFile)) {
                        include $viewFile;
                    } else {
                        include __DIR__ . '/perfil-views/datosUser.php';
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="perfil-right">
        <div class="user-head">
            <div class="user-name">
                <p><?= htmlspecialchars($_SESSION['username'] ?? 'Nombre del Usuario') ?></p>
            </div>
            <div class="user-picture">
                <img src="img/profile-picture.png" alt="Profile Picture">
            </div>
        </div>
        <div class="user-details">
            <div class="details-section">
                <h3>Datos de Usuario</h3>
                <div class="detail-item"><?= htmlspecialchars($_SESSION['email'] ?? 'user@example.com') ?></div>
                <div class="detail-item"><?= htmlspecialchars($_SESSION['telefono'] ?? '+52 55 1234 5678') ?></div>
                <div class="detail-item"><?= htmlspecialchars($_SESSION['direccion'] ?? 'Calle Falsa 123, Col. Centro, CDMX') ?></div>
            </div>
            <br><br><br><br>
        </div>
        <nav class="user-nav">
            <button class="nav-button" onclick="location.href='index.php?action=cartdetails'">Canasta</button>
            <button class="nav-button" onclick="location.href='index.php?action=checkout'">Pedidos</button>
            <button class="nav-button" onclick="location.href='index.php?action=perfil'">Información de usuario</button>
        </nav>
    </div>
</div>

<?php include_once './views/templates/footer.php'; ?>