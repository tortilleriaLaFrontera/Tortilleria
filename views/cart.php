<?php 
// Safety check
if(!isset($cartItems)) {
    $cartItems = [];
}

$isDropdown = $isDropdown ?? false;
?>

<?php if($isDropdown): ?>
    <div class="cart-dropdown-view">
<?php else: ?>
    <div class="cart-full-view">
        <h2>Tu Carrito de Compras</h2>
<?php endif; ?>

    <?php if(empty($cartItems)): ?>
        <div class="cart-empty">El carrito está vacío</div>
    <?php else: ?>
        <?php foreach($cartItems as $item): ?>
            <?php if(!isset($item['id'])) continue; ?>
            <div class="cart-item">
                <?php if(!$isDropdown): ?>
                    <img src="<?= htmlspecialchars($item['imagen'] ?? '') ?>" alt="<?= htmlspecialchars($item['nombre'] ?? '') ?>">
                <?php endif; ?>
                <span class="cart-item-name"><?= htmlspecialchars($item['nombre'] ?? '') ?></span>
                <span class="cart-item-qty">x<?= $item['cantidad'] ?? 1 ?></span>
                
                <a href="index.php?action=remove_from_cart&id=<?= $item['id'] ?><?= $isDropdown ? '&from_dropdown=1' : '' ?>" 
                   class="cart-item-remove" 
                   onclick="return confirm('¿Eliminar este artículo?')">🗑️</a>
            </div>
        <?php endforeach; ?>

        <div class="cart-actions">
            <?php if($isDropdown): ?>
                <a href="index.php?action=cart_view" class="view-cart">Ver Carrito Completo</a>
            <?php else: ?>
                <div class="cart-total">
                    Total: $<?= number_format($cartController->getCartTotal(), 2) ?>
                </div>
                <a href="index.php?action=checkout" class="checkout-btn">Proceder al Pago</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>