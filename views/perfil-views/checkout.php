<div id="checkout-tab">
    <h3>Resumen de Compra</h3>
    <div class="checkout-cart-container">
        <?php 
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                include './views/partials/cart-item-checkout.php';
            }
            include './views/partials/checkout-total.php';
        } else {
            echo '<div class="empty-cart">No hay items en el carrito</div>';
        }
        ?>
    </div>
    <!-- Area checkout -->
</div>