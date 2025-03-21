import { registerUser } from "../servicios/userService.js";

// Esperar a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function () {
    const registrationForm = document.getElementById("registrationForm");
    const errorMessage = document.getElementById("errorMessage");

    // Validación de campos
    function validarCampo(campo, validacion, errorId) {
        const errorElement = document.getElementById(errorId);
        let esValido = validacion(campo.value);

        if (!esValido) {
            campo.classList.add('invalid');
            errorElement.style.display = 'block';
        } else {
            campo.classList.remove('invalid');
            errorElement.style.display = 'none';
        }

        return esValido;
    }

    // Validaciones
    const validaciones = {
        nombre: value => value.trim().length >= 3, // Nombre debe tener al menos 3 caracteres
        correo: value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value), // Correo debe ser válido
        direccion: value => value.trim().length >= 5, // Dirección debe tener al menos 5 caracteres
        telefono: value => /^\d{10}$/.test(value) // Teléfono debe tener 10 dígitos
    };

    // Activar/Desactivar el botón de enviar
    function activarBotonEnviar() {
        const nombreValido = validaciones.nombre(document.getElementById('nombre').value);
        const correoValido = validaciones.correo(document.getElementById('correo').value);
        const direccionValida = validaciones.direccion(document.getElementById('direccion').value);
        const telefonoValido = validaciones.telefono(document.getElementById('telefono').value);

        // Habilitar el botón solo si todos los campos son válidos
        if (nombreValido && correoValido && direccionValida && telefonoValido) {
            document.getElementById('submitButton').disabled = false;
        } else {
            document.getElementById('submitButton').disabled = true;
        }
    }

    // Validación dinámica
    document.getElementById('nombre').addEventListener('input', function () {
        validarCampo(this, validaciones.nombre, 'nombreError');
        activarBotonEnviar();
    });

    document.getElementById('correo').addEventListener('input', function () {
        validarCampo(this, validaciones.correo, 'correoError');
        activarBotonEnviar();
    });

    document.getElementById('direccion').addEventListener('input', function () {
        validarCampo(this, validaciones.direccion, 'direccionError');
        activarBotonEnviar();
    });

    document.getElementById('telefono').addEventListener('input', function () {
        validarCampo(this, validaciones.telefono, 'telefonoError');
        activarBotonEnviar();
    });

    // Inicializar el botón como desactivado
    document.getElementById('submitButton').disabled = true;

    // Manejar el envío del formulario de registro
    registrationForm.addEventListener("submit", async (event) => {
        event.preventDefault(); // Prevenir el envío del formulario

        // Validar todos los campos antes de enviar
        const nombreValido = validarCampo(document.getElementById('nombre'), validaciones.nombre, 'nombreError');
        const correoValido = validarCampo(document.getElementById('correo'), validaciones.correo, 'correoError');
        const direccionValida = validarCampo(document.getElementById('direccion'), validaciones.direccion, 'direccionError');
        const telefonoValido = validarCampo(document.getElementById('telefono'), validaciones.telefono, 'telefonoError');

        // Si algún campo no es válido, mostrar mensaje de error
        if (!nombreValido || !correoValido || !direccionValida || !telefonoValido) {
            errorMessage.textContent = "Por favor, complete todos los campos correctamente.";
            return;
        }

        // Obtener los valores del formulario
        const nombre = document.getElementById("nombre").value;
        const correo = document.getElementById("correo").value;
        const direccion = document.getElementById("direccion").value;
        const telefono = document.getElementById("telefono").value;

        try {
            // Registrar al usuario
            const userId = await registerUser(nombre, correo, direccion, telefono, null); // No se proporciona contraseña
            console.log("Usuario registrado con id:", userId);

            // Redirigir a la página de inicio
            window.location.href = "index.html";
        } catch (error) {
            // Mostrar mensaje de error si falla el registro
            errorMessage.textContent = error.message;
        }
    });
});