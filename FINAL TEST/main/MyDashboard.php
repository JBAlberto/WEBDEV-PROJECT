<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en" x-data="dashboard()" x-init="loadPackages()">
<head>
  <meta charset="UTF-8">
  <title>My Dashboard - BoniGlobe Express</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="design/Mdash.css">
  <link rel="icon" href="images/turboLogo.png">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body>

<header>
  <div class="left-section">
    <img src="images/turboLogo.png" alt="BoniGlobe Logo" style="height: 40px;">
  </div>
  <div class="center-section">
    <div class="logo-text">BoniGlobe Express</div>
    <div class="tagline">From Our Door to Yours—Fast.</div>
  </div>
  <nav class="right-section show">
    <a href="dashboard.php"><button>Home</button></a>
  </nav>
</header>

<main style="padding: 24px;">
  <span class="mes">
  <h2>My Sent Packages</h2>
  
  <div x-show="loading" class="spinner"></div>

  <template x-if="!loading && packages.length === 0">
    <p>No packages found.</p>
  </template>
</span>

  <div class="card-grid" x-show="!loading">
    <template x-for="pkg in packages" :key="pkg.id">
      <div class="package-card" x-transition>
        <p><strong>Receiver:</strong> <span x-text="pkg.contact"></span></p>
        <p><strong>To:</strong> <span x-text="pkg.location_2"></span></p>
        <p><strong>Type:</strong> <span x-text="pkg.packtype"></span></p>
        <p><strong>Packaging:</strong> <span x-text="pkg.packagingType"></span></p>
        <p><strong>Status:</strong>
          <span x-text="pkg.delivered ? 'Delivered' : (pkg.approved ? 'Approved' : (pkg.rejected ? 'Rejected' : 'Pending'))"></span>
        </p>
        <button @click="deletePackage(pkg.id)">Delete</button>
      </div>
    </template>
  </div>
</main>

<script>
  function dashboard() {
    return {
      packages: [],
      loading: true,

      loadPackages() {
        fetch('Backend/dash.php')
          .then(res => res.json())
          .then(data => {
            this.packages = data;
            this.loading = false;
          })
          .catch(err => {
            console.error("Failed to load packages:", err);
            this.loading = false;
          });
      },

      deletePackage(id) {
        if (!confirm("Are you sure you want to delete this package?")) return;

        fetch('Backend/deletepackage.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            this.packages = this.packages.filter(pkg => pkg.id !== id);
            alert("Package deleted.");
          } else {
            alert("Deletion failed: " + data.message);
          }
        })
        .catch(err => {
          console.error("Error deleting:", err);
          alert("An error occurred.");
        });
      }
    }
  }
</script>

</body>
</html>
