<div class="cart-item" data-id="<?= $item['id'] ?>">
    <img src="<?= $item['imagen'] ?>" alt="<?= $item['nombre'] ?>">
    <div class="item-details">
        <h4><?= $item['nombre'] ?></h4>
        <div class="item-controls">
            <button class="qty-btn minus">-</button>
            <span class="quantity"><?= $item['cantidad'] ?></span>
            <button class="qty-btn plus">+</button>
        </div>
    </div>
    <div class="item-price">$<?= number_format($item['costo'] * $item['cantidad'], 2) ?></div>
    <button class="remove-item" data-id="<?= $item['id'] ?>">🗑️</button>
</div>