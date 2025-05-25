<?php

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$package_id = $data['package_id'] ?? null;
$packaging_type = $data['packaging_type'] ?? null;

if (!$package_id || !$packaging_type) {
    echo json_encode(['error' => 'Missing required fields.']);
    exit;
}

$feeTable = [
    'Primary' => 5.00,
    'Secondary' => 10.00,
    'Tertiary' => 15.00,
];

$shipping_fee = $feeTable[$packaging_type] ?? null;
if ($shipping_fee === null) {
    echo json_encode(['error' => 'Invalid packaging type.']);
    exit;
}

// Connection to DB (adjust if needed)
$conn = new mysqli('localhost', 'root', 'root', 'testdbproject');

$stmt = $conn->prepare("UPDATE packages SET packaging_type = ?, shipping_fee = ? WHERE id = ?");
$stmt->bind_param("sdi", $packaging_type, $shipping_fee, $package_id);
if (!$stmt->execute()) {
    echo json_encode(['error' => 'Failed to update package.']);
    exit;
}

$result = $conn->query("SELECT tracking_number, name, location_1, location_2, packtype FROM packages WHERE id = $package_id");
if ($result && $result->num_rows > 0) {
    $package = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'shipping_fee' => $shipping_fee,
        'package_info' => $package
    ]);
} else {
    echo json_encode(['error' => 'Package not found.']);
}

$conn->close();
?>