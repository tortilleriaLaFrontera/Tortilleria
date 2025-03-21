<?php
// adminAddPassword.php

header("Content-Type: application/json"); // Set response type to JSON

// Include the database connection and usuarioRepo.php
require_once 'dbConnect.php';
require_once 'usuarioRepo.php';

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

// Extract email and hashed password from the request
$email = $data["email"] ?? null;
$hashedPassword = $data["hashedPassword"] ?? null;

if (!$email || !$hashedPassword) {
    echo json_encode([
        "success" => false,
        "message" => "Faltan campos requeridos"
    ]);
    exit();
}

try {
    // Find the user by email
    $user = getUsrConMail($email);

    if (!$user) {
        throw new Exception("Usuario no encontrado");
    }

    // Update the user's password in the database
    $stmt = $conn->prepare("UPDATE usuario SET pwhashed = ? WHERE correo = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $hashedPassword, $email);
    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Password updated successfully"
        ]);
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>