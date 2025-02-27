function abrirmenu() {
    const enlaces = document.querySelector('.links');
    enlaces.classList.toggle('active');
    
}

//para cerrar el menu si hago click en otra parte
document.addEventListener('click', function (event) {
    const menu = document.querySelector('.links');
    const btnMenu = document.querySelector('.menu');

    if (!menu.contains(event.target) && !btnMenu.contains(event.target)) {
        menu.classList.remove('active');
    }
})