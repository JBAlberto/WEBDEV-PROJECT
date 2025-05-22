<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en" x-data="dashboard()">
<head>
  <link rel="stylesheet" href="design/homedesign.css" />
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body x-data="dashboard()" x-init="init()">
  <header>
    <a href="dashboard.php" class="logo">boniglobe</a>
    <nav>
      <a href="sending.html">Send a Package</a>
      <a href="track-package.html">Track a Package</a>
    </nav>
    <template x-if="username">
      <form method="POST" action="Backend/logout.php">
        <button type="submit">Logout</button>
      </form>
    </template>
  </header>
  <main class="container">
    <template x-if="username">
      <h2>Welcome, <span x-text="username"></span>!</h2>
    </template>
    <template x-if="!username">
      <p>Checking login...</p>
    </template>

  <script>
    function dashboard() {
      return {
        username: null,
        init() {
          fetch('Backend/session.php')
            .then(res => res.json())
            .then(data => {
              if (data.loggedIn) {
                this.username = data.username;
              } else {
                window.location.href = 'login.html';
              }
            });
        }
      }
    }
  </script>
</body>
</html>
