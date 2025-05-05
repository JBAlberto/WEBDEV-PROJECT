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

$error = "";

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
            header("Location: ../mainmenu.html");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that email.";
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
  <title>BoniGlobe Sign in</title>
</head>

<body>
  <div class="left-section">
    <img src="../IMAGES/turboLogo.png" alt="BoniGlobe Logo" height="90" />
  </div>
  <h3 style="color: aqua;">Before anything else</h3>
  <h1 style="color: white;">Sign In to BoniGlobe Today!</h1>
  <form class="form" method="POST" action="loginpage.php">
    <span class="input-span">
      <label for="email" class="label">Email</label>
      <input type="email" name="email" id="email" required />
    </span>
    <span class="input-span">
      <label for="password" class="label">Password</label>
      <input type="password" name="password" id="password" required />
    </span>
    <input class="submit" type="submit" value="Log in" />
    <?php if ($error): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <span class="span">Don't have an account? <a href="signup.php">Register</a> now</span>
  </form>
</body>

</html>