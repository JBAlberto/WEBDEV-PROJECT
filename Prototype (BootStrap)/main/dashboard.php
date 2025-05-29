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
  <title>Main Menu - BoniGlobe Express</title>
  <link rel="icon" href="design/images/turboLogo.png">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    body {
      background-color: #121f2b;
      color: white;
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .map-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: -1;
      pointer-events: none;
      filter: brightness(0.4);
    }
    .navbar-brand img {
      height: 50px;
    }
    .dashboard-content {
      text-align: center;
      padding: 4rem 2rem;
    }
    .btn-custom {
      background-color: #1bb4cf;
      color: white;
      margin: 0.5rem;
    }
    .btn-custom:hover {
      background-color: #149bb0;
    }
    #footerlogo {
      text-align: center;
      margin-top: 3rem;
      padding: 2rem 0;
      border-top: 1px solid #333;
    }
    .spinner {
      width: 3rem;
      height: 3rem;
      border: 0.4rem solid #ccc;
      border-top-color: #1bb4cf;
      border-radius: 50%;
      margin: 3rem auto;
      animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="design/images/turboLogo.png" alt="Logo" />
      <span class="ms-2">BoniGlobe Express</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar" x-data="{ infoOpen: false, dashOpen: false }">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="information.html">Information</a></li>
        <li class="nav-item"><a class="nav-link" href="MyDashboard.php">Dashboard</a></li>


        <li class="nav-item">
          <form method="POST" action="Backend/logout.php" class="d-inline" @submit.prevent="confirmLogout">
            <button type="submit" class="btn btn-danger ms-2">Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Map Background -->
  <div class="map-background">
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d6604.5496882403095!2d120.55946091512126!3d18.05684378012015!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTjCsDAzJzI5LjEiTiAxMjDCsDMzJzM0LjUiRQ!5e0!3m2!1sen!2sph!4v1748432900417!5m2!1sen!2sph"
      width="100%"
      height="100%"
      style="border:0;"
      allowfullscreen=""
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>

  <!-- Main Content -->
  <main class="dashboard-content" x-data>
    <!-- Loading Spinner -->
    <template x-if="loading">
      <div class="spinner"></div>
    </template>

    <!-- Welcome Section -->
    <template x-if="!loading && username">
      <div>
        <h2 class="mb-4">Welcome, <span x-text="username"></span>!</h2>
        <a href="sending.html" class="btn btn-custom">Send a Package</a>
        <a href="track-package.html" class="btn btn-custom">Track a Package</a>
      </div>
    </template>

    <!-- Fallback -->
    <template x-if="!loading && !username">
      <p class="text-info">Checking login...</p>
    </template>
  </main>

  <!-- Footer -->
  <div id="footerlogo">
    <img src="design/images/turboLogo.png" height="80" alt="BoniGlobe Logo" />
    <h5 class="mt-3">BoniGlobe Express</h5>
    <a href="https://www.facebook.com/mr.abbo.505" target="_blank">
      <button class="btn btn-primary mt-2">Facebook</button>
    </a>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
