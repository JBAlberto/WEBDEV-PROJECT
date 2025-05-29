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
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>
    .map-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: -1;
      pointer-events: none;
    }
  </style>
</head>
<body class="relative min-h-screen bg-gray-900 text-white flex flex-col">

  <!-- Background Map -->
  <div class="map-background">
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d6604.5496882403095!2d120.55946091512126!3d18.05684378012015!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTjCsDAzJzI5LjEiTiAxMjDCsDMzJzM0LjUiRQ!5e0!3m2!1sen!2sph!4v1748432900417!5m2!1sen!2sph"
      width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>

  <!-- Header -->
  <header x-data="{ menuOpen: false, infoOpen: false, dashOpen: false }"
    class="w-full bg-black bg-opacity-60 backdrop-blur-md px-6 py-4 flex items-center justify-between relative z-10">
    
    <!-- Logo -->
    <div class="flex items-center gap-3">
      <img src="design/images/turboLogo.png" alt="BoniGlobe Logo" class="w-10 h-10"/>
      <div>
        <h1 class="text-lg font-bold">BoniGlobe Express</h1>
        <p class="text-sm text-gray-300">From Our Door to Yours—Fast.</p>
      </div>
    </div>

    <!-- Hamburger -->
    <button @click="menuOpen = !menuOpen" class="md:hidden text-2xl">&#9776;</button>

    <!-- Navigation -->
    <nav :class="menuOpen ? 'flex' : 'hidden'" class="md:flex flex-col md:flex-row gap-4 items-end md:items-center mt-4 md:mt-0">
      
      <!-- Information Dropdown -->
      <div class="relative" @click.away="infoOpen = false">
        <a href="information.html" class="hover:underline">
        <button @click="infoOpen = !infoOpen" class="hover:underline">Information</button>
        </a>
        <div x-show="infoOpen" x-transition
          class="absolute top-full mt-2 bg-white text-black p-4 rounded shadow w-64 z-20">
          <p>Welcome to BoniGlobe Express. Fast and reliable package delivery.</p>
        </div>
      </div>

      <!-- Dashboard Dropdown -->
      <div class="relative" @click.away="dashOpen = false">
        <a href="Mydashboard.php" class="hover:underline">
        <button @click="dashOpen = !dashOpen" class="hover:underline">Dashboard</button>
        </a>
      </div>

      <!-- Home -->
      <a href="dashboard.php" class="hover:underline">Home</a>

      <!-- Logout -->
      <form method="POST" action="Backend/logout.php" @submit.prevent="confirmLogout">
        <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded">
          Logout
        </button>
      </form>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="flex-1 flex flex-col justify-center items-center px-6 z-10 text-center">

    <!-- Loading Spinner -->
    <template x-if="loading">
      <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-white mb-4"></div>
    </template>

    <!-- Welcome Content -->
    <template x-if="!loading && username">
      <div class="bg-black bg-opacity-60 backdrop-blur-md p-8 rounded-lg shadow-md space-y-4">
        <h2 class="text-3xl font-bold">Welcome, <span x-text="username"></span>!</h2>
        <div class="flex flex-col md:flex-row gap-4 justify-center">
          <a href="sending.html" class="bg-yellow-400 text-black font-semibold px-6 py-3 rounded hover:bg-yellow-300 transition">Send a Package</a>
          <a href="track-package.html" class="bg-blue-500 text-white font-semibold px-6 py-3 rounded hover:bg-blue-400 transition">Track a Package</a>
        </div>
      </div>
    </template>

    <!-- Fallback -->
    <template x-if="!loading && !username">
      <p class="text-teal-300">Checking login...</p>
    </template>
  </main>

  <!-- Footer -->
  <footer class="w-full text-center text-sm bg-black bg-opacity-60 backdrop-blur-md py-4 z-10">
    <div class="flex flex-col items-center space-y-2">
      <img src="design/images/turboLogo.png" alt="Logo" class="h-12">
      <span>BoniGlobe Express</span>
      <a href="https://www.facebook.com/mr.abbo.505" target="_blank" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded">Facebook</a>
    </div>
  </footer>

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
