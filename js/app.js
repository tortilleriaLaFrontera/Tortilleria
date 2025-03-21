// visualizacion de menu en visualizacion de dispositivos moviles
function abrirmenu() {
    const enlaces = document.querySelector('.links');
    enlaces.classList.toggle('active');
    
}
// cambio de form segun se desea registrar o iniciar sesion de usuario
function cambiarform() {
    const regArea = document.querySelector('.registro');
    const loginArea = document.querySelector('.login');
    regArea.classList.toggle('enuso');
    loginArea.classList.toggle('enuso');
}

//para cerrar el menu si hago click en otra parte
document.addEventListener('click', function (event) {
    const menu = document.querySelector('.links');
    const btnMenu = document.querySelector('.menu');
    

    if (!menu.contains(event.target) && !btnMenu.contains(event.target)) {
        menu.classList.remove('active');
    }
    
})


// login

import { loginUser } from "../servicios/userService.js";

// abrir popup
function openLoginPopup(event) {
    event.preventDefault(); // prevenir funcion original del link
    const popup = document.getElementById("loginPopup");
    popup.style.display = "block";
}

// cerrar popup
function closeLoginPopup() {
    const popup = document.getElementById("loginPopup");
    popup.style.display = "none";
}

// envio de formulario
document.getElementById("loginForm").addEventListener("submit", async (event) => {
    event.preventDefault(); // quitar func original

    const correo = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    try {
        const user = await loginUser(correo, password);
        console.log("User logged in:", user);
        closeLoginPopup(); // cerar el popup
        window.location.href = "index.html"; // manda a index si inicia sesión
    } catch (error) {
        alert(error.message); // error por si no
    }
});


// asignacion de listeners al link de login y boton de cerrar dentro del popup
document.querySelector(".login-container a").addEventListener("click", openLoginPopup);
document.querySelector(".popup .close").addEventListener("click", closeLoginPopup);