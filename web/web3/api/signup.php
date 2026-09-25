<?php
header("Content-Type: application/json");
include "db.php";

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

$full_name = $data["fullName"] ?? "";
$email = strtolower(trim($data["email"] ?? ""));
$password = $data["password"] ?? "";
$language = $data["language"] ?? "en";

if (!$full_name || !$email || !$password) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

// Check if email exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email already registered"]);
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert user
$insert = $conn->prepare("INSERT INTO users (full_name, email, password, language) VALUES (?, ?, ?, ?)");
$insert->bind_param("ssss", $full_name, $email, $hashedPassword, $language);

if ($insert->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to register"]);
}

$conn->close();
?>