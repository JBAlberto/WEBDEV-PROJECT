<?php

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$package_id = $data['package_id'] ?? null;

if (!$package_id) {
    echo json_encode(['error' => 'Missing package ID.']);
    exit;
}

// Connect to DB (adjust credentials)
$conn = new mysqli('localhost', 'root', 'root', 'testdbproject');

$stmt = $conn->prepare("UPDATE packages SET delivery_status = 'Accepted' WHERE id = ?");
$stmt->bind_param("i", $package_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to update delivery status.']);
}

$conn->close();
?>