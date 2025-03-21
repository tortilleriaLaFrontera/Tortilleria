//#region login
// Iniciar sesión
async function loginUser(correo, password) {
    const response = await fetch("./data/login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ correo, password })
    });

    const data = await response.json();

    if (!data.success) {
        throw new Error(data.message);
    }

    // Guardar la sesión en sessionStorage
    sessionStorage.setItem("user", JSON.stringify(data.user));

    return data.user;
}

// Cerrar sesión
function logoutUser() {
    sessionStorage.removeItem("user");
    window.location.href = "index.html"; // Regresar al index
}

// Verificar si hay sesión activa
function isLoggedIn() {
    return !!sessionStorage.getItem("user");
}

// Obtener el usuario actual
function getCurrentUser() {
    return JSON.parse(sessionStorage.getItem("user"));
}
//#endregion

//#region registro
// Registrar usuario nuevo
async function registerUser(nombre, correo, direccion, telefono, pwhashed = null) {
    const response = await fetch("data/registro.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ nombre, correo, direccion, telefono, pwhashed })
    });

    const data = await response.json();

    if (!data.success) {
        throw new Error(data.message);
    }

    return data.userId; // Regresar el id del usuario creado
}
//#endregion

// Exportar funciones
export { loginUser, logoutUser, isLoggedIn, getCurrentUser, registerUser };