<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en" x-data="dashboard()" x-init="init()">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - BoniGlobe Express</title>
  <link rel="stylesheet" href="design/dashboard.css">
  <link rel="icon" href="images/turboLogo.png">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body style="background-image: url('images/map.JPG'); background-repeat: no-repeat; background-size: cover;">

  <header x-data="{ menuOpen: false, infoOpen: false, dashOpen: false }">
    <div class="left-section">
      <img src="images/turboLogo.png" alt="BoniGlobe Logo" />
    </div>

    <div class="dropdown" @click.away="infoOpen = false">
      <a href="information.html">
      <button @click="infoOpen = !infoOpen">Information</button>
      </a>
      <div class="dropdown-content" x-show="infoOpen" x-transition>
        <p>Welcome to BoniGlobe Express. Fast and reliable package delivery.</p>
      </div>
    </div>

    <div class="center-section">
      <div class="logo-text">BoniGlobe Express</div>
      <div class="tagline">From Our Door to Yours—Fast.</div>
    </div>

    <div class="hamburger" @click="menuOpen = !menuOpen">&#9776;</div>

    <!-- Navigation -->
    <nav class="right-section" :class="{ 'show': menuOpen }">
      <div class="dropdown">
        <a href="dashboard.php"><button>HOME</button></a>
      </div>

      <div class="dropdown" @click.away="dashOpen = false">
        <button @click="dashOpen = !dashOpen">Dashboard</button>
        <div class="dropdown-content" x-show="dashOpen" x-transition>
          <a href="Mydashboard.php">My Dashboard</a>
          <a href="sending.html">Send a Package</a>
          <a href="track-package.html">Track a Package</a>
        </div>
      </div>

      <div class="dropdown">
        <form method="POST" action="Backend/logout.php" @submit.prevent="confirmLogout">
          <button style="background-color: red;" type="submit">Logout</button>
        </form>
      </div>
    </nav>
  </header>

  <main>
    <!-- Loading Spinner -->
    <template x-if="loading">
      <div class="spinner"></div>
    </template>

    <!-- Welcome Content -->
    <template x-if="!loading && username">
      <div class="head">
        <h2>Welcome, <span x-text="username"></span>!</h2>
        <a href="sending.html" class="facebook">Send a Package</a><br />
        <a href="track-package.html" class="facebook">Track a Package</a>
      </div>
    </template>

    <!-- Fallback if no username found -->
    <template x-if="!loading && !username">
      <p style="color: #1bb4cf;">Checking login...</p>
    </template>
  </main>

  <div id="footerlogo">
    <img src="images/turboLogo.png" height="100" alt="BoniGlobe Logo" />
    <span class="footer-text">BoniGlobe Express</span>
    <div>
      <a href="https://www.facebook.com/mr.abbo.505" target="_blank">
        <button class="facebook">Facebook</button>
      </a>
    </div>
  </div>

  <script>
    function dashboard() {
      return {
        username: null,
        loading: true,
        init() {
          fetch('Backend/session.php')
            .then(res => res.json())
            .then(data => {
              if (data.loggedIn) {
                this.username = data.username;
              } else {
                window.location.href = 'login.html';
              }
            })
            .catch(() => window.location.href = 'login.html')
            .finally(() => this.loading = false);
        },
        confirmLogout() {
          if (confirm("Are you sure you want to log out?")) {
            document.querySelector('form[action="Backend/logout.php"]').submit();
          }
        }
      }
    }
  </script>
</body>
</html>
