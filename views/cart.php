<?php 
// Safety check at the beginning
if(!isset($cartItems)) {
    $cartItems = [];
}
?>

<?php if(isset($isDropdown) && $isDropdown): ?>
    <div class="cart-dropdown-view">
<?php else: ?>
    <div class="cart-full-view">
<?php endif; ?>

    <?php if(empty($cartItems)): ?>
        <div class="cart-empty">El carrito está vacío</div>
    <?php else: ?>
        <?php foreach($cartItems as $item): ?>
            <?php if(!isset($item['id'])) continue; // ignora elementos invalidos ?>
            <div class="cart-item">
                <span class="cart-item-name"><?= htmlspecialchars($item['nombre'] ?? '') ?></span>
                <span class="cart-item-qty">x<?= $item['cantidad'] ?? 1 ?></span>
                <?php if(isset($isDropdown) && $isDropdown): ?>
                    <a href="index.php?action=remove_from_cart&id=<?= $item['id'] ?>&from_dropdown=1" 
                       class="cart-item-remove" 
                       onclick="return confirm('¿Eliminar este artículo?')">🗑️</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div class="cart-actions">
            <a href="index.php?action=cart" class="view-cart">Ver Carrito Completo</a>
        </div>
    <?php endif; ?>
</div>