<?php include_once './views/templates/header.php'; ?>
<div class="perfil-main">
<div class="perfil-left">
    <div class="perfil-left-head">
        <h2><?php echo $currentWindow ?></h2>
    </div>
    <div class="perfil-left-body">
        <div class="left-body-content">
            <?php
            if ($currentWindow) {
                $view = $currentWindow;
                $viewFile = "./views/perfil-views/{$view}.php";
                if (file_exists($viewFile)) {
                    include $viewFile;
                } else {
                    include './views/perfil-views/datosUser.php';
                }
            } else {
                // Default profile view
                include './views/perfil-views/datosUser.php';
            }
            ?>
        </div>
    </div>
</div>
    <div class="perfil-right">
        <div class="user-head">
            <div class="user-name">
                <p>Nombre del Usuario</p>
            </div>
            <div class="user-picture">
                <img src="profile-picture.jpg" alt="Profile Picture">
            </div>
        </div>
        <div class="user-details">
            <div class="details-section">
                <h3>Datos de Usuario</h3>
                <div class="detail-item">user@example.com</div>
                <div class="detail-item">+52 55 1234 5678</div>
                <div class="detail-item">Calle Falsa 123, Col. Centro, CDMX</div>
            </div>
            <br><br><br><br>
        </div>
        <nav class="user-nav">
            <button class="nav-button">Canasta</button>
            <button class="nav-button">Pedidos</button>
            <button class="nav-button">Información de usuario</button>
        </nav>
    </div>
</div>

<?php include_once './views/templates/footer.php'; ?>