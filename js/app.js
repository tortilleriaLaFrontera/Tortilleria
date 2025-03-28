document.addEventListener('DOMContentLoaded', function () {
    // Menu functionality
    function abrirmenu() {
        const enlaces = document.querySelector('.links');
        enlaces.classList.toggle('active');
    }

    // Form switching functionality
    function cambiarform() {
        const regArea = document.querySelector('.registro');
        const loginArea = document.querySelector('.login');
        regArea.classList.toggle('enuso');
        loginArea.classList.toggle('enuso');
    }

    // Close menu when clicking outside
    document.addEventListener('click', function (event) {
        const menu = document.querySelector('.links');
        const btnMenu = document.querySelector('.menu');

        if (!menu.contains(event.target) && !btnMenu.contains(event.target)) {
            menu.classList.remove('active');
        }
    });

    // modificacion de funcion original del toggle de carrito
    document.querySelector('.cart-toggle')?.addEventListener('click', function(e) {
        if (window.innerWidth > 768) { // solo pa pc
            e.preventDefault();
            document.querySelector('.cart-content').classList.toggle('show');
        }
    });
    
    // cerrar dropdown al hacer click afuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.cart-dropdown')) {
            document.querySelector('.cart-content')?.classList.remove('show');
        }
    });
    
    // Assign event listeners
    document.querySelector('.menu').addEventListener('click', abrirmenu);
    document.querySelector('.cambiar-form').addEventListener('click', cambiarform);
});
