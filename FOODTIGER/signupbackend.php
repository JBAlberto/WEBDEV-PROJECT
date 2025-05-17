<?php
$host = 'localhost';
$db = 'boniglobe_db'; // name database
$user = 'root';
$pass = 'root'; // pass database
// kinupyak lng ty dati ngem ninayunak json na kasi needed nu usaren ty jy  @submit.prevent="register" ngem mabalin nu awan json na ngem maikkat ty @submit
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//ibagbaga na lng jy browser nga json ti response nga alaen
header('Content-Type: application/json');
//array for responses
$response = [];

// Run only if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // sanitize input
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $response['error'] = "Invalid email format.";
      return;
    }

    // Check for existing email
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();
    if ($check_email->num_rows > 0) {
        $response['error'] = "An account with this email already exists.";
    }
    $check_email->close();

    // pag wala errorprocced to database
    if (empty($response)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        $query->bind_param("sss", $fullname, $email, $hashed_password);
        if ($query->execute()) {
          $response['success'] = true;
        } else {
          $response['error'] = "Something went wrong. Please try again.";
          //pang check lng ulit if ever adda error na query ngem mabalin py maiikat detuy 
        }
        $query->close();
    }
}

$conn->close();
echo json_encode($response);//send back tay response

?>