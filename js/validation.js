document.addEventListener('DOMContentLoaded', function() {
    // Obtener formularios
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    
    // Validar formulario de inicio de sesión
    if(loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            let hasErrors = false;
            let errorMessages = [];
            
            // Validar email
            if(!email) {
                errorMessages.push('El correo es obligatorio');
                hasErrors = true;
            } else if(!isValidEmail(email)) {
                errorMessages.push('El formato del correo es inválido');
                hasErrors = true;
            }
            
            // Validar contraseña
            if(!password) {
                errorMessages.push('La contraseña es obligatoria');
                hasErrors = true;
            }
            
            // Si hay errores, evitar envío del formulario
            if(hasErrors) {
                e.preventDefault();
                alert(errorMessages.join('\n'));
            }
        });
    }
    
    // Validar formulario de registro
    if(registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const direccion = document.getElementById('direccion').value;
            const telefono = document.getElementById('telefono').value;
            const password = document.getElementById('password').value;
            let hasErrors = false;
            let errorMessages = [];
            
            // Validar nombre de usuario
            if(!username) {
                errorMessages.push('El nombre de usuario es obligatorio');
                hasErrors = true;
            }
            
            // Validar email
            if(!email) {
                errorMessages.push('El correo es obligatorio');
                hasErrors = true;
            } else if(!isValidEmail(email)) {
                errorMessages.push('El formato del correo es inválido');
                hasErrors = true;
            }
            
            // Validar contraseña
            if(!password) {
                errorMessages.push('La contraseña es obligatoria');
                hasErrors = true;
            } else if(password.length < 6) {
                errorMessages.push('La contraseña debe tener al menos 6 caracteres');
                hasErrors = true;
            }
            
            //validar dirección
            if(!direccion) {
                errorMessages.push('La dirección es obligatoria');
                hasErrors = true;
            }

            // Validar telefono
            if(!telefono) {
                errorMessages.push('El telefono es obligatoria');
                hasErrors = true;
            } else if(!/^\d+$/.test(telefono)) {  // Verifica que solo sean números
                errorMessages.push('El telefono solo debe contener números');
                hasErrors = true;
            } else if(telefono.length < 10) {
                errorMessages.push('El telefono debe tener al menos 6 caracteres');
                hasErrors = true;
            }
            

            // Si hay errores, evitar envío del formulario
            if(hasErrors) {
                e.preventDefault();
                alert(errorMessages.join('\n'));
            }
        });
    }
    
    // Función para validar formato de email
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});