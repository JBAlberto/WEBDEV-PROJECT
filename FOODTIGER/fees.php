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
  <link rel="stylesheet" href="design/informationpage.css">
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
    <img id="headprof" src="/images/profile.png" width="30" alt="Profile" style="cursor: pointer;">
    <span>My Account: <?php echo htmlspecialchars($_SESSION['email']); ?></span>
    <h2 id="start">Shipping Fees</h2>
  </div>
  
  <main class="calculatefee">
    <div x-data="tabsCalculator()" class="container">
      <h3 style="color: rgb(0, 0, 0); text-align: center; font-family: font1 ,'Courier New', Courier, monospace; font-size: small;">With the Lowest Shipping Fees</h3><br>
      <div class="tabs">
        <div class="tab" :class="{ 'active': tab === 'locald' }" @click="tab = 'locald'">Local</div>
        <div class="tab" :class="{ 'active': tab === 'intld' }" @click="tab = 'intld'">International</div>
      </div>
    
      <div x-show="tab === 'locald'">
        <label>From:</label>
        <select x-model="fromLocal" @change="updateDistance('local')">
          <option value="">Select</option>
          <template x-for="(val, key) in locations.local">
            <option :value="key" x-text="key"></option>
          </template>
        </select>
    
        <label>To:</label>
        <select x-model="toLocal" @change="updateDistance('local')">
          <option value="">Select</option>
          <template x-for="(val, key) in locations.local">
            <option :value="key" x-text="key"></option>
          </template>
        </select>
        
        <div class="result">
          Total Fee: Php <span x-text="localTotal.toFixed(2)"></span>
        </div>
      </div>
    
      <div x-show="tab === 'intld'">
        <label>To:</label>
        <select x-model="toIntl" @change="updateDistance('intl')">
          <option value="">Select</option>
          <template x-for="(distance, destination) in locations.international['Asia(Philippines)']">
            <option :value="destination" x-text="destination"></option>
          </template>
        </select>
    
        <div class="result">
          Total Fee: Php <span x-text="intlTotal.toFixed(2)"></span>
        </div>
      </div>
    </div>
  </main>

  <div id="footerlogo">
    <img src="/images/turboLogo.png" height="100" alt="BoniGlobe Logo">
    <span class="footer-text">BoniGlobe Express</span>
    <div class="flex items-center justify-center h-full">
      <a href="https://www.facebook.com/mr.abbo.505" target="_blank">
        <button class="facebook">Facebook</button>
      </a>
    </div>
  </div>

  <script>
  function tabsCalculator() {
    return {
      tab: 'locald',
      baseFee: 5.00,
      ratePerKm: 1.25,

      locations: {
        local: {
          'Luzon': { 'Luzon': 0, 'Visayas': 10, 'Mindanao': 15 },
          'Visayas': { 'Luzon': 10, 'Visayas': 0, 'Mindanao': 8 },
          'Mindanao': { 'Luzon': 15, 'Visayas': 8, 'Mindanao': 0 }
        },
        international: {
          'Asia(Philippines)': {
            'Asia(International)': 25,
            'Europe': 40,
            'Africa': 65,
            'Australia': 55,
            'South America': 75,
            'North America': 100
          }
        }
      },

      fromLocal: '',
      toLocal: '',
      distance1: 0,

      fromIntl: 'Asia(Philippines)',
      toIntl: '',
      distance2: 0,

      updateDistance(scope) {
        if (scope === 'local' && this.fromLocal && this.toLocal) {
          this.distance1 = this.locations.local[this.fromLocal][this.toLocal] || 0;
        }
        if (scope === 'intl' && this.fromIntl && this.toIntl) {
          this.distance2 = this.locations.international[this.fromIntl][this.toIntl] || 0;
        }
      },

      get localTotal() {
        return this.baseFee + (this.distance1 * this.ratePerKm);
      },

      get intlTotal() {
        return this.baseFee + (this.distance2 * 2 * this.ratePerKm);
      }
    };
  }
  </script>
</body>
</html>
