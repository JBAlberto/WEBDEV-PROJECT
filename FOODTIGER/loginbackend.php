<?php
session_start(); // store user speccific info or create baro or resume activity

$host = "localhost";
$user = "root";
$password = "root"; // password jy datbase tayo
$dbname = "boniglobe_db"; // nagan

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}

header('Content-Type: application/json');
$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            
            $response['success'] = true;
            echo json_encode($response);
            exit;
        } else {
            $response['error'] = "Invalid password.";
            echo json_encode($response);
            exit;
        }
    } else {
        $response['error'] = "No user found with that email.";
        echo json_encode($response);
        exit;
    }
}

$conn->close();
?>