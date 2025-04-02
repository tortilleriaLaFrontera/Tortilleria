<!-- File: perfil-views/checkout.php -->
<div class="orders-container">
    <?php if (empty($orders)): ?>
        <div class="no-orders-message">
            <p>No existe registro de órdenes del usuario</p>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
        <div class="order-card">
            <!-- Order Header -->
            <div class="order-header">
                <div class="order-number">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></div>
                <div class="order-date"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></div>
                <div class="order-status <?= str_replace(' ', '-', strtolower($order['estado'])) ?>">
                    <?= $order['estado'] ?>
                </div>
            </div>
            
            <!-- Order Products -->
            <div class="order-products">
                <?php foreach ($order['items'] as $item): ?>
                <div class="product-row">
                    <span class="product-name"><?= $item['nombre'] ?></span>
                    <span class="product-quantity">x<?= $item['cantidad'] ?></span>
                    <span class="product-price">$<?= number_format($item['precio_unitario'], 2) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Order Footer -->
            <div class="order-footer">
                <div class="order-total">
                    Total: <span>$<?= number_format($order['total'], 2) ?></span>
                </div>
                <div class="order-delivery">
                    <?= $order['tipo_entrega'] === 'envio' ? 'Envío a domicilio' : 'Recoger en tienda' ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>