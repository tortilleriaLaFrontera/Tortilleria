</main>
    <?php if(!isset($_SESSION['user_id']) && $page != 'register' && $page != 'login'): ?>
        <section class="strip">
            <p class="strip-1p">Listo para ordenar?</p>
            <p class="strip-2p">Registrate ahora y disfruta de la facilidad<br>de hacer pedidos en linea</p>
            <a href="index.php?action=register"><button>Registrate aqui!</button></a>
        </section>
    <?php endif; ?>
    <script src="./js/validation.js"></script>
    <?php if(isset($_SESSION['user_id'])): ?>
        <script src="./js/app.js"></script>
        <script src="./js/gestionCarrito.js"></script>
    <?php endif; ?>
</body>
</html>