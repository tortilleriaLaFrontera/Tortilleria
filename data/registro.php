<?php
// registro.php

header("Content-Type: application/json"); // Set response type to JSON

// Include the database connection
require_once 'dbConnect.php';

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
$password = $data["password"] ?? null;

if (!$nombre || !$correo || !$direccion || !$password) {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields"
    ]);
    exit();
}

try {
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Call the Data Access Layer to create a user
    $userId = crearUsr($nombre, $correo, $direccion, $hashedPassword);

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