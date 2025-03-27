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
<!-- <div class="dashboard">
    
    <h2>Bienvenido, <?php echo $_SESSION['username']; ?>!</h2>
    
    <div class="card">
        <h3>Panel de Control</h3>
        <p>Esta es una página protegida que sólo puede ser vista por usuarios autenticados.</p>
        <p>Aquí puedes añadir cualquier funcionalidad adicional para tu aplicación.</p>
    </div>
    
    <div class="actions">
        <a href="index.php?action=logout" class="btn">Cerrar Sesión</a>
    </div>
</div> -->

<?php include_once './views/templates/footer.php'; ?>