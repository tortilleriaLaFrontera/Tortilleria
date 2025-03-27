<?php include_once './views/templates/header.php'; ?>

<div class="form-container">
    <h2 class="formTitle">Solicitud de Cuenta</h2>
    
    <?php if(isset($_SESSION['errors'])): ?>
        <div class="message error">
            <ul>
                <?php 
                foreach($_SESSION['errors'] as $error) {
                    echo "<li>{$error}</li>";
                }
                unset($_SESSION['errors']);
                ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form action="index.php?action=register" method="post" id="register-form">
        <div class="form-group">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>
        </div>

        <div class="form-group">
            <label for="telefono">Telefono:</label>
            <input type="text" id="telefono" name="telefono" required>
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <button type="submit">Registrarse</button>
        </div>
        
        <p>¿Ya tienes una cuenta? <a href="index.php?action=login" class="enlaceCambio">Inicia sesión aquí</a></p>
    </form>
</div>

<?php include_once './views/templates/footer.php'; ?>