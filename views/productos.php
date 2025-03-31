<?php include_once './views/templates/header.php'; ?>

<!-- Cuerpo -->


<div class="caja-flotante">

    <?php if (!empty($products)): ?>

    <?php foreach ($products as $product): ?>

    <span class="producto-area" data-product-id="<?= $product['id'] ?>">
        <img src="<?= htmlspecialchars($product['imagen']) ?>" alt="<?= htmlspecialchars($product['imagen']) ?>">
        <span class="product-info">
            <p class="producto-nombre"><?= htmlspecialchars($product['nombre']) ?></p>
            <p class="producto-descripcion"><?= htmlspecialchars($product['descripcion']) ?></p>
        </span>
        <button class="aCanasta" data-product-id="<?= $product['id'] ?>">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M221-120q-27 0-48-16.5T144-179L42-549q-5-19 6.5-35T80-600h190l176-262q5-8 14-13t19-5q10 0 19 5t14 13l176 262h192q20 0 31.5 16t6.5 35L816-179q-8 26-29 42.5T739-120H221Zm-1-80h520l88-320H132l88 320Zm260-80q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM367-600h225L479-768 367-600Zm113 240Z"/></svg>
        </button>
    </span>

    <?php endforeach; ?>

    <?php else: ?>
    <span class="producto-area" data-product-id="">
        <span class="product-info">
            <p class="producto-nombre"></p>
            <p class="producto-descripcion">No hay productos disponibles</p>
        </span>
    </span>
    <?php endif; ?>
</div>
<?php include_once './views/templates/footer.php'; ?>