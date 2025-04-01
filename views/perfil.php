<?php include_once './views/templates/header.php'; ?>
<div class="perfil-main">
    <div class="perfil-left">
        <div class="perfil-left-head">
            <!-- Left header content can go here -->
        </div>
        <div class="perfil-left-body">
            <div class="left-body-content">
                <!-- Scrollable content rows -->
                <div class="content-row">Row 1</div>
                <div class="content-row">Row 2</div>
                <div class="content-row">Row 3</div>
                <!-- More rows as needed -->
            </div>
            <div class="left-body-controls">
                <!-- Control buttons -->
                <div class="control-button">Control 1</div>
                <div class="control-button">Control 2</div>
                <div class="control-button">Control 3</div>
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
            <button class="edit-profile">Editar perfil</button>
            <div class="details-section">
                <h3>Datos de Usuario</h3>
                <div class="detail-item">user@example.com</div>
                <div class="detail-item">+52 55 1234 5678</div>
                <div class="detail-item">Calle Falsa 123, Col. Centro, CDMX</div>
            </div>
        </div>
        <nav class="user-nav">
            <button class="nav-button">Canasta</button>
            <button class="nav-button">Pedidos</button>
            <button class="nav-button">Información de usuario</button>
        </nav>
    </div>
</div>

<?php include_once './views/templates/footer.php'; ?>