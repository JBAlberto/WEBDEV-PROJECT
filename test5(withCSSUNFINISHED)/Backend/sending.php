<?php
// sending.php

header("Content-Type: application/json");

// Read the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
  echo json_encode(["message" => "Invalid input."]);
  exit;
}

// Database connection
$host = 'localhost';
$db   = 'testdbproject';
$user = 'root';         // Change as needed
$pass = 'root';             // Change as needed
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

if (
  !isset($data['name']) || trim($data['name']) === '' ||
  !isset($data['contact']) || trim($data['contact']) === '' ||
  !isset($data['number']) || trim($data['number']) === '' ||
  !isset($data['location_1']) || trim($data['location_1']) === '' ||
  !isset($data['location_2']) || trim($data['location_2']) === '' ||
  !isset($data['packtype']) || trim($data['packtype']) === ''
) {
  echo json_encode(['success' => false, 'message' => 'All fields are required.']);
  exit;
}

try {
  $pdo = new PDO($dsn, $user, $pass, $options);

  $tracking_number = uniqid("PKG-");

  // Prepare and execute insert statement
  $stmt = $pdo->prepare("INSERT INTO packages 
    (user_id, name, contact, number, location_1, location_2, packtype, tracking_number) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

  $stmt->execute([
    $data['user_id'],
    $data['name'],
    $data['contact'],
    $data['number'],
    $data['location_1'],
    $data['location_2'],
    $data['packtype'],
    $tracking_number
  ]);

  echo json_encode([
    "success" => true,
    "message" => "Package info saved successfully. $tracking_number",
    //"tracking_number" => $tracking_number
  ]);
} catch (PDOException $e) {
  echo json_encode(["message" => "Database error: " . $e->getMessage()]);
}
?>