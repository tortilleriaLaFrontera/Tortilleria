<div id="canasta-tab">
    <h3>Tu Canasta</h3>
    <div class="cart-container">
        <?php 
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                include './views/partials/cart-item-detailed.php';
            }
            echo '<div class="cart-total">Total: $'.number_format($cartTotal, 2).'</div>';
        } else {
            echo '<div class="empty-cart">El carrito está vacío</div>';
        }
        ?>
    </div>
</div>