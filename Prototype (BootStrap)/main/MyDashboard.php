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
  <link rel="icon" href="design/images/turboLogo.png">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    header {
      background-color: #121f2b;
      color: white;
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .logo-text {
      font-size: 1.5rem;
      font-weight: bold;
    }

    .tagline {
      font-size: 0.9rem;
      color: #ccc;
    }

    .spinner {
      width: 3rem;
      height: 3rem;
      border: 0.4rem solid #ccc;
      border-top-color: #1bb4cf;
      border-radius: 50%;
      margin: 2rem auto;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .package-card {
      background-color: white;
      border: 1px solid #dee2e6;
      border-radius: 0.5rem;
      padding: 1rem;
      margin-bottom: 1rem;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      transition: transform 0.2s;
    }

    .package-card:hover {
      transform: scale(1.01);
    }

    .btn-delete {
      background-color: #dc3545;
      color: white;
    }

    .btn-delete:hover {
      background-color: #bb2d3b;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <div class="d-flex align-items-center">
      <img src="design/images/turboLogo.png" alt="BoniGlobe Logo" style="height: 40px; margin-right: 10px;">
      <div>
        <div class="logo-text">BoniGlobe Express</div>
        <div class="tagline">From Our Door to Yours—Fast.</div>
      </div>
    </div>
    <a href="dashboard.php" class="btn btn-light">Home</a>
  </header>

  <!-- Main Content -->
  <main class="container py-5">
    <h2 class="mb-4 text-center">My Sent Packages</h2>

    <!-- Loading Spinner -->
    <div x-show="loading" class="spinner"></div>

    <!-- No Packages -->
    <template x-if="!loading && packages.length === 0">
      <p class="text-center text-muted">No packages found.</p>
    </template>

    <!-- Package Cards -->
    <div class="row" x-show="!loading">
      <template x-for="pkg in packages" :key="pkg.id">
        <div class="col-md-6 col-lg-4">
          <div class="package-card">
            <p><strong>Receiver:</strong> <span x-text="pkg.contact"></span></p>
            <p><strong>To:</strong> <span x-text="pkg.location_2"></span></p>
            <p><strong>Type:</strong> <span x-text="pkg.packtype"></span></p>
            <p><strong>Packaging:</strong> <span x-text="pkg.packagingType"></span></p>
            <p><strong>Status:</strong>
              <span x-text="pkg.delivered ? 'Delivered' : (pkg.approved ? 'Approved' : (pkg.rejected ? 'Rejected' : 'Pending'))"></span>
            </p>
            <button class="btn btn-delete mt-2 w-100" @click="deletePackage(pkg.id)">Delete</button>
          </div>
        </div>
      </template>
    </div>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Alpine.js Logic -->
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
