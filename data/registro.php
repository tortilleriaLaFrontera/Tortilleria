<?php
// registro.php

header("Content-Type: application/json"); // Set response type to JSON

// Include the database connection and usuarioRepo.php
require_once 'dbConnect.php';
require_once 'usuarioRepo.php'; // Include the file where crearUsr() is defined

// Get the raw POST data
$input = file_get_contents("php://input");
if (!$input) {
    echo json_encode([
        "success" => false,
        "message" => "No input data received"
    ]);
    exit();
}

$data = json_decode($input, true);
if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON data"
    ]);
    exit();
}

// Extract user data from the request
$nombre = $data["nombre"] ?? null;
$correo = $data["correo"] ?? null;
$direccion = $data["direccion"] ?? null;
$telefono = $data["telefono"] ?? null;

if (!$nombre || !$correo || !$direccion || !$telefono) {
    echo json_encode([
        "success" => false,
        "message" => "Faltan campos requeridos"
    ]);
    exit();
}

try {
    // Call the Data Access Layer to create a user
    $userId = crearUsr($nombre, $correo, $direccion, $telefono);

    // Return a success response with the user ID
    echo json_encode([
        "success" => true,
        "userId" => $userId
    ]);
} catch (Exception $e) {
    // Return an error response
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>