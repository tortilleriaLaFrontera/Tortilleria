<?php

header("Content-Type: application/json"); // las respuestas seran en formato JSON

// conn a db
require_once 'dbConnect.php';

// recibir los datos de POST (raw)
$data = json_decode(file_get_contents("php://input"), true);

// vars para mail y pw
$correo = $data["correo"];
$password = $data["password"];

try {
    // LLamado a capa de datos para recibir el usuario con mail
    $user = getUsrConMail($correo);

    if (!$user) {
        throw new Exception("User not found");
    }

    // validacion de pw hasheado
    if (password_verify($password, $user["password"])) {
        // responder 'success' con los datos
        echo json_encode([
            "success" => true,
            "user" => [
                "id" => $user["id"],
                "nombre" => $user["nombre"],
                "correo" => $user["correo"]
            ]
        ]);
    } else {
        throw new Exception("Credenciales no validas");
    }
} catch (Exception $e) {
    // responder con el error
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>