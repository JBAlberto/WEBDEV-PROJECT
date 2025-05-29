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
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen">

  <!-- Header -->
  <header class="bg-black text-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
      <div class="flex items-center gap-4">
        <img src="design/images/turboLogo.png" alt="BoniGlobe Logo" class="h-10">
        <div>
          <h1 class="text-lg font-bold">BoniGlobe Express</h1>
          <p class="text-sm text-gray-300">From Our Door to Yours—Fast.</p>
        </div>
      </div>
      <nav>
        <a href="dashboard.php">
          <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white">Home</button>
        </a>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6">My Sent Packages</h2>

    <!-- Loading Spinner -->
    <div x-show="loading" class="flex justify-center items-center my-10">
      <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <!-- No Packages Message -->
    <template x-if="!loading && packages.length === 0">
      <p class="text-center text-gray-500">No packages found.</p>
    </template>

    <!-- Package Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-show="!loading">
      <template x-for="pkg in packages" :key="pkg.id">
        <div class="bg-white shadow rounded-lg p-5 border border-gray-200 hover:shadow-md transition" x-transition>
          <p><strong>Receiver:</strong> <span x-text="pkg.contact"></span></p>
          <p><strong>To:</strong> <span x-text="pkg.location_2"></span></p>
          <p><strong>Type:</strong> <span x-text="pkg.packtype"></span></p>
          <p><strong>Packaging:</strong> <span x-text="pkg.packagingType"></span></p>
          <p><strong>Status:</strong>
            <span 
              class="font-medium"
              :class="{
                'text-green-600': pkg.delivered,
                'text-yellow-500': pkg.approved && !pkg.delivered,
                'text-red-600': pkg.rejected,
                'text-gray-500': !pkg.approved && !pkg.delivered && !pkg.rejected
              }"
              x-text="pkg.delivered ? 'Delivered' : (pkg.approved ? 'Approved' : (pkg.rejected ? 'Rejected' : 'Pending'))">
            </span>
          </p>
          <button 
            @click="deletePackage(pkg.id)"
            class="mt-4 w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded transition">
            Delete
          </button>
        </div>
      </template>
    </div>
  </main>

  <!-- AlpineJS Dashboard Logic -->
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
