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
<html lang="en" x-data="loginForm()">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../design/logindesign.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <title>BoniGlobe Sign in</title>
</head>

<body>

  <div class="">
    <img src="../images/turboLogo.png" alt="BoniGlobe Logo" height="90">
  </div>

  <h3 id="text1" class="Texts">Before anything else</h3>
  <h1 id="text2" class="Texts">Sign In to BoniGlobe Today!</h1>

  <form class="form" @submit.prevent="submit">
    <span class="input-span">
      <label for="email" class="label">Email</label>
      <input type="email" id="email" x-model="email" required />
    </span>
    <span class="input-span">
      <label for="password" class="label">Password</label>
      <input type="password" id="password" x-model="password" required />
    </span>
    <input class="submit" type="submit" value="Log in" />
    <?php if ($error): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <span class="span">Don't have an account? <a href="signuppage.php">Register</a> now</span>
  </form>

  <script>
  function loginForm() {
    return {
      email: '',
      password: '',
      async submit() {
        const formData = new FormData();
        formData.append('email', this.email);
        formData.append('password', this.password);

        try {
          const response = await fetch('', {
            method: 'POST',
            body: formData
          });

          const result = await response.text();
          this.message = result;

          // Optional: clear fields on success
          if (result.includes('successful')) {
            this.email = '';
            this.password = '';
            window.location.href = "../main/mainmenu.html";
          }
        } catch (error) {
          this.message = 'Error submitting form.';
          console.error(error);
        }
      }
    }
  }
  </script>

</body>

</html>