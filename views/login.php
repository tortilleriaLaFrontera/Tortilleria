<?php include_once './views/templates/header.php'; ?>

<div class="form-container">
    <h2 class="formTitle">Iniciar Sesión</h2>
    
    <?php if(isset($_SESSION['message'])): ?>
        <div class="message success">
            <?php 
            echo $_SESSION['message']; 
            unset($_SESSION['message']);
            ?>
        </div>
    <?php endif; ?>
    
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
    
    <form action="index.php?action=login" method="post" id="login-form">
        <div class="form-group">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <button type="submit">Iniciar Sesión</button>
        </div>
        
        <p>¿No tienes una cuenta? <a href="index.php?action=register" class="enlaceCambio">Regístrate aquí</a></p>
    </form>
</div>

<?php include_once './views/templates/footer.php'; ?>