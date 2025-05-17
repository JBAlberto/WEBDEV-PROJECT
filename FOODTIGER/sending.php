<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: loginpage2.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" x-data="{ menuOpen: false }">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BoniGlobe Express</title>
  <link rel="stylesheet" href="design/homedesign.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body>
  <header>
    <div class="left-section">
      <img src="images/turboLogo.png" alt="BoniGlobe Logo">
    </div>

    <div class="center-section">
      <div class="logo-text">BoniGlobe Express</div>
      <div class="tagline">From Our Door to Yours—Fast.</div>
    </div>

    <div class="hamburger" @click="menuOpen = !menuOpen">
      &#9776;
    </div>

    <nav class="right-section" :class="{ 'show': menuOpen }">
      <div id="homebutton" class="dropdown">
        <a href="mainmenu.php">
          <button>HOME</button>
        </a>
      </div>
      <div class="dropdown">
        <button>Information</button>
        <div class="dropdown-content">
          <a href="fees.php">Shipping Fees</a>
          <a href="days.php">Shipping Days</a>
        </div>
      </div>
      <div class="dropdown">
        <button>Packages</button>
        <div class="dropdown-content">
          <a href="mainmenu.php">My Packages</a>
          <a href="sending.php">Send a Package</a>
        </div>
      </div>
    </nav>
  </header>

  <div class="head">
    <img id="headprof" src="images/profile.png" width="30" alt="Profile" style="cursor: pointer;">
    <span>My Account: <?php echo htmlspecialchars($_SESSION['email']); ?></span>
    <h2 id="start">You are Logged in!</h2>
  </div>

  <div id="footerlogo">
    <img src="images/turboLogo.png" height="100" alt="BoniGlobe Logo">
    <span class="footer-text">BoniGlobe Express</span>
    <div class="flex items-center justify-center h-full">
      <a href="https://www.facebook.com/mr.abbo.505" target="_blank">
        <button class="facebook">Facebook</button>
      </a>
    </div>
  </div>
</body>
</html>
