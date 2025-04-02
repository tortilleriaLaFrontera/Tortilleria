<!-- File: perfil-views/datosUser.php -->
<div class="user-profile-container">
    <?php
    // Sample data - replace with actual user data from your system
    $userData = [
        'username' => $_SESSION['username'] ?? 'johndoe',
        'email' => $_SESSION['email'] ?? 'john@example.com',
        'direccion' => $_SESSION['direccion'] ?? 'Calle Falsa 123, CDMX',
        'telefono' => $_SESSION['telefono'] ?? '55 1234 5678',
        'created_at' => $_SESSION['created_at'] ?? '2023-01-15',
        'active_orders' => 2 // This should come from your database query
    ];
    ?>
    
    <div class="profile-header">
        <h3>Información de Perfil</h3>
        <div class="member-since">
            Miembro desde: <?= date('F Y', strtotime($userData['created_at'])) ?>
        </div>
    </div>
    
    <!-- <div class="profile-stats">
        <div class="stat-card">
            <div class="stat-value"><?= $userData['active_orders'] ?></div>
            <div class="stat-label">Órdenes activas</div>
        </div>
    </div> -->
    
    <form class="profile-form" id="userProfileForm">
        <div class="form-group">
            <label for="username">Nombre de usuario</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($userData['username']) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required disabled>
            <small class="form-note">Contacta al administrador para cambiar el correo</small>
        </div>
        
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($userData['direccion']) ?>">
        </div>
        
        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="tel" id="telefono" name="telefono" value="<?= htmlspecialchars($userData['telefono']) ?>">
        </div>
        
        <div class="form-group">
            <label for="current_password">Contraseña actual (para cambios)</label>
            <input type="password" id="current_password" name="current_password">
        </div>
        
        <div class="form-group">
            <label for="new_password">Nueva contraseña</label>
            <input type="password" id="new_password" name="new_password">
            <small class="form-note">Dejar en blanco para no cambiar</small>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmar nueva contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password">
        </div>
        
        <div class="form-actions">
            <button type="button" class="cancel-btn">Cancelar</button>
            <button type="submit" class="save-btn">Guardar cambios</button>
        </div>
    </form>
</div>