<?php include_once './views/templates/header.php'; ?>

    <?php if(!isset($_SESSION['user_id'])): ?>

        <section class="caja-flotante">
            <p>El Arte de la<br>Tortilla de Harina</p>
            <div>
                <button class="boton-base"><a href="index.php?action=nosotros">Conocenos</a></button>
                <button class="boton-base"><a href="index.php?action=register">Unete!</a></button>
            </div>
        </section>

    <?php else: ?>

        <section class="caja-flotante">
            <p>El Arte de la<br>Tortilla de Harina</p>
            <div>
                <button class="boton-base"><a href="index.php?action=producto">Ver Producto</a></button>
                <button class="boton-base"><a href="index.php?action=perfil">Perfil</a></button>
            </div>
        </section>
        
    <?php endif; ?>

<?php include_once './views/templates/footer.php'; ?>