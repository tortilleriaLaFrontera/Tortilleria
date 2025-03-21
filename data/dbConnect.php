<?php

$host = "localhost";
$username = "root";
$password = "";
$db = "tortilleria";

$conn = new mysqli($host, $username, $password, $db);

if ($conn->connect_error) {
    die("Coneccion fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8");

return $conn;
?>