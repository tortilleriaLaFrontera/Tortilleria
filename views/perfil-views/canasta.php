<div id="canasta-tab">
    <h3>Tu Canasta</h3>
    <div class="cart-container">
        <?php if (!empty($cartItems)): ?>
            <?php foreach ($cartItems as $item): ?>
            <div class="cart-item" data-id="<?= $item['id'] ?>">
                <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>">
                <div class="item-details">
                    <h4><?= htmlspecialchars($item['nombre']) ?></h4>
                    <p><?= htmlspecialchars($item['descripcion']) ?></p>
                    <div class="item-controls">
                        <button class="qty-btn minus" data-id="<?= $item['id'] ?>">-</button>
                        <span class="quantity"><?= $item['cantidad'] ?></span>
                        <button class="qty-btn plus" data-id="<?= $item['id'] ?>">+</button>
                    </div>
                </div>
                <div class="item-price">$<?= number_format($item['costo'] * $item['cantidad'], 2) ?></div>
                <button class="remove-item" data-id="<?= $item['id'] ?>">🗑️</button>
            </div>
            <?php endforeach; ?>
            <div class="cart-total">Total: $<?= number_format($cartTotal, 2) ?></div>
        <?php else: ?>
            <div class="empty-cart">El carrito está vacío</div>
        <?php endif; ?>
    </div>
</div>