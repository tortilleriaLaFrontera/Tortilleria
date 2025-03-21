//#region login
// iniciar sesion
async function loginUser(correo, password) {
    try {
        const response = await fetch("../data/login.php", {
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

        // usar localstorage para guardar la sesion
        sessionStorage.setItem("user", JSON.stringify(data.user));

        return data.user;
    } catch (error) {
        console.error("Error al intentar iniciar sesión:", error);
        throw error;
    }
}

// Cerrar sesion del usuario (por implementar)
function logoutUser() {
    sessionStorage.removeItem("user");
    window.location.href = "index.html"; // de regreso al index
}

// checkar si hay sesion activa
function isLoggedIn() {
    return !!sessionStorage.getItem("user");
}

// pedir el usuario actual
function getCurrentUser() {
    return JSON.parse(sessionStorage.getItem("user"));
}
//#endregion
//#region registro
// registrar usr nuevo
async function registerUser(nombre, correo, direccion, password) {
    try {
        const response = await fetch("data/registro.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ nombre, correo, direccion, password })
        });

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message);
        }

        return data.userId; // regresar el id de usr creado
    } catch (error) {
        console.error("Error al registrar usuario:", error);
        throw error;
    }
}
//#endregion
// export de funciones en script
export { loginUser, logoutUser, isLoggedIn, getCurrentUser, registerUser };