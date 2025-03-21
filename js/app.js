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

    // Assign event listeners
    document.querySelector('.menu').addEventListener('click', abrirmenu);
    document.querySelector('.cambiar-form').addEventListener('click', cambiarform);
});

// login

// Import loginUser from userService.js
import { loginUser } from '../servicios/userService.js'; // Adjusted path

document.addEventListener('DOMContentLoaded', function () {
    // Open login popup
    function openLoginPopup(event) {
        event.preventDefault();
        const popup = document.getElementById("loginPopup");
        popup.style.display = "block";
    }

    // Close login popup
    function closeLoginPopup() {
        const popup = document.getElementById("loginPopup");
        popup.style.display = "none";
    }

    // Handle login form submission
    async function handleLoginFormSubmit(event) {
        event.preventDefault();

        const correo = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        try {
            const user = await loginUser(correo, password);
            console.log("User logged in:", user);
            closeLoginPopup();
            window.location.href = "index.html";
        } catch (error) {
            alert(error.message);
        }
    }

    // Assign event listeners
    document.querySelector(".login-container a").addEventListener("click", openLoginPopup);
    document.querySelector(".popup .close").addEventListener("click", closeLoginPopup);
    document.getElementById("loginForm").addEventListener("submit", handleLoginFormSubmit);
});