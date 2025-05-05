<?php
$host = 'localhost';
$db = 'boniglobe_db'; // name database
$user = 'root';
$pass = 'root'; // pass database

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$erroremail = $errorpw = '';

// Run only if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // sanitize input
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    //  check password
    if ($password !== $confirm_password) {
        $errorpw = "Passwords do not match!";
    }

    // Check for existing email
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();
    if ($check_email->num_rows > 0) {
        $erroremail = "An account with this email already exists.";
    }
    $check_email->close();

    // pag wala errorprocced to database
    if (empty($erroremail) && empty($errorpw)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        $query->bind_param("sss", $fullname, $email, $hashed_password);

        if ($query->execute()) {
            header("Location: loginpage.php");
            exit();
        } else {
            $erroremail = "Something went wrong. Please try again.";
        }

        $query->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../logindesign.css" />
  <title>BoniGlobe Sign Up</title>
</head>

<body>
  <div class="left-section">
    <img src="../IMAGES/turboLogo.png" alt="BoniGlobe Logo" height="90" />
  </div>
  <h3 style="color: aqua;">Join BoniGlobe Today</h3>
  <h1 style="color: white;">Create Your Account</h1>
  <form class="form" action="" method="POST">
    <span class="input-span">
      <label for="fullname" class="label">Full Name</label>
      <input type="text" name="fullname" id="fullname" required />
    </span>
    <span class="input-span">
      <label for="email" class="label">Email</label>
      <input type="email" name="email" id="email" required />
      <?php if ($erroremail): ?>
      <p style="color: red;"><?php echo htmlspecialchars($erroremail); ?></p>
      <?php endif; ?>
    </span>
    <span class="input-span">
      <label for="password" class="label">Password</label>
      <input type="password" name="password" id="password" required />
    </span>
    <span class="input-span">
      <label for="confirm_password" class="label">Confirm Password</label>
      <input type="password" name="confirm_password" id="confirm_password" required />
      <?php if ($errorpw): ?>
      <p style="color: red;"><?php echo htmlspecialchars($errorpw); ?></p>
      <?php endif; ?>
    </span>
    <input class="submit" type="submit" value="Sign Up" />
  </form>
</body>

</html>