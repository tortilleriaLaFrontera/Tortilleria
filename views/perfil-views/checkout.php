<div class="checkout-view">
    <h2>Proceso de Pago</h2>
    
    <div class="checkout-steps">
        <!-- Step 1: Review Cart -->
        <div class="checkout-step active" data-step="1">
            <h3>1. Revisa tu pedido</h3>
            <?php include './views/cart.php'; ?>
        </div>
        
        <!-- Step 2: Delivery Info -->
        <div class="checkout-step" data-step="2">
            <h3>2. Información de envío</h3>
            <form id="delivery-form">
                <!-- Delivery form fields -->
            </form>
        </div>
        
        <!-- Step 3: Payment -->
        <div class="checkout-step" data-step="3">
            <h3>3. Método de pago</h3>
            <!-- Payment options -->
        </div>
    </div>
    
    <div class="checkout-actions">
        <button class="btn-prev">Anterior</button>
        <button class="btn-next">Siguiente</button>
        <button class="btn-confirm" style="display:none">Confirmar Pedido</button>
    </div>
</div>