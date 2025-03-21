
import { registerUser } from "../servicios/userService.js";

const registrationForm = document.getElementById("registrationForm");
const errorMessage = document.getElementById("errorMessage");

registrationForm.addEventListener("submit", async (event) => {
    event.preventDefault(); // Prevent the form from submitting

    const nombre = document.getElementById("nombre").value;
    const correo = document.getElementById("correo").value;
    const direccion = document.getElementById("direccion").value;
    const password = document.getElementById("password").value;

    try {
        const userId = await registerUser(nombre, correo, direccion, password);
        console.log("User registered with ID:", userId);
        window.location.href = "index.html"; // Redirect to the home page
    } catch (error) {
        errorMessage.textContent = error.message;
    }
});