<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

$host = 'localhost';
$user = 'root';
$pass = 'root';
$db   = 'testdbproject';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

$tracking_number = $_GET['tracking_number'] ?? '';

if ($tracking_number === '') {
  echo json_encode(['error' => 'Tracking number is required']);
  exit;
}

$stmt = $conn->prepare("
  SELECT id, approved, tracking_number, name, location_1, location_2, packtype
  FROM packages
  WHERE tracking_number = ?
");
if (!$stmt) {
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit;
}

$stmt->bind_param("s", $tracking_number);
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  echo json_encode([
    'approved' => (int)$row['approved'],
    'package_id' => (int)$row['id'],
    'tracking_number' => $row['tracking_number'],
    'name' => $row['name'],
    'location_1' => $row['location_1'],
    'location_2' => $row['location_2'],
    'packtype' => $row['packtype']
  ]);
} else {
  echo json_encode(['error' => 'Package not found']);
}

$stmt->close();
$conn->close();
?>