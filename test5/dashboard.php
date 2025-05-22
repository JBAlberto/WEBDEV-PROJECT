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
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body x-data="dashboard()" x-init="init()">
  <template x-if="username">
    <div>
      <h2>Welcome, <span x-text="username"></span>!</h2>
      <form method="POST" action="Backend/logout.php">
        <button type="submit">Logout</button>
      </form>
    </div>
  </template>
    <div x-data="{ link: 'sending.html' }">
    <a x-bind:href="link">Send a Package</a>
    <div x-data="{ link: 'track-package.html' }">
    <a x-bind:href="link">Track a Package</a>
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
