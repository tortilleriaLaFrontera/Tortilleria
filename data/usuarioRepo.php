<?php

require_once 'dbConnect.php';

// creacion de usuario
function crearUsr($nombre, $correo, $direccion, $password) {
    global $conn;

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO usuario (nombre, correo, direccion, password, activo) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters and execute the query
    $activo = false; // Set 'activo' to false by default
    $stmt->bind_param("ssssi", $nombre, $correo, $direccion, $password, $activo);
    if ($stmt->execute()) {
        return $stmt->insert_id; // Return the ID of the newly created user
    } else {
        die("Execute failed: " . $stmt->error);
    }
}

// extraer id de usuario
function getUsrConId($id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE id = ?");
    if (!$stmt) {
        die("Fallo en preparación: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // retornar arreglo de datos de usr
    } else {
        die("Fallo en ejecución: " . $stmt->error);
    }
}

// utilizar el mail para encontrar usr
function getUsrConMail($correo) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE correo = ?");
    if (!$stmt) {
        die("Fallo en preparación: " . $conn->error);
    }

    $stmt->bind_param("s", $correo);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    } else {
        die("Fallo en ejecución: " . $stmt->error);
    }
}

// actualizar datos de usuario
function actualizarUsr($id, $nombre, $telefono, $direccion) {
    global $conn;

    // preparacion del query
    $stmt = $conn->prepare("UPDATE usuario SET nombre = ?, telefono = ?, direccion = ? WHERE id = ?");
    if (!$stmt) {
        die("Fallo en preparación: " . $conn->error);
    }

    // assoc de params y ejecucion
    $stmt->bind_param("sssi", $nombre, $telefono, $direccion, $id);
    if ($stmt->execute()) {
        return $stmt->affected_rows > 0; // true si fue exitoso
    } else {
        die("Fallo en ejecución: " . $stmt->error);
    }
}

// Desactivar usr (cambiar a true o false)
function activarUser($id) {
    global $conn;

    $stmt = $conn->prepare("UPDATE usuario SET activo = ? WHERE id = ?");
    if (!$stmt) {
        die("Fallo en preparación: " . $conn->error);
    }

    $activo = true;
    $stmt->bind_param("ii", $activo, $id);
    if ($stmt->execute()) {
        return $stmt->affected_rows > 0; // contestar con true si se activo
    } else {
        die("Fallo en ejecución: " . $stmt->error);
    }
}

function deleteUsr($id) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM usuario WHERE id = ?");
    if (!$stmt) {
        die("Fallo en preparación: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        return $stmt->affected_rows > 0; // true si se elimino
    } else {
        die("Fallo en ejecución: " . $stmt->error);
    }
}
?>