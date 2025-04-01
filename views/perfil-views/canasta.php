
<div class="cart-profile-view">
    <h3>Tu Canasta</h3>
    
    <?php if(empty($cartItems)): ?>
        <div class="cart-empty">El carrito está vacío</div>
    <?php else: ?>
        <div class="cart-items-container">
            <?php foreach($cartItems as $item): ?>
            <div class="content-row cart-item-row">
                <div class="cart-item-image">
                    <img src="<?= htmlspecialchars($item['imagen'] ?? '') ?>" 
                         alt="<?= htmlspecialchars($item['nombre'] ?? '') ?>">
                </div>
                <div class="cart-item-details">
                    <span class="item-name"><?= htmlspecialchars($item['nombre'] ?? '') ?></span>
                    <span class="item-price">$<?= number_format($item['costo'] ?? 0, 2) ?></span>
                </div>
                <div class="cart-item-actions">
                    <div class="quantity-controls">
                        <button class="qty-btn minus" data-item-id="<?= $item['id'] ?>">-</button>
                        <span class="item-qty"><?= $item['cantidad'] ?? 1 ?></span>
                        <button class="qty-btn plus" data-item-id="<?= $item['id'] ?>">+</button>
                    </div>
                    <button class="remove-item" data-item-id="<?= $item['id'] ?>">
                        🗑️ Eliminar
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="cart-summary">
            <div class="total-amount">
                Total: $<?= number_format($cartController->getCartTotal(), 2) ?>
            </div>
            <a href="index.php?action=checkout" class="checkout-btn">Proceder al Pago</a>
        </div>
    <?php endif; ?>
</div>
<!-- <div class="perfil-left-head"> -->
    <!-- Left header content can go here -->
<!-- </div>
<div class="perfil-left-body">
    <div class="left-body-content"> -->
        <!-- Scrollable content rows -->
        <!-- <div class="content-row">Row 1</div>
        <div class="content-row">Row 2</div>
        <div class="content-row">Row 3</div> -->
        <!-- More rows as needed -->
    <!-- </div>
    <div class="left-body-controls"> -->
        <!-- Control buttons -->
        <!-- <div class="control-button">Control 1</div>
        <div class="control-button">Control 2</div>
        <div class="control-button">Control 3</div>
    </div>
</div> -->