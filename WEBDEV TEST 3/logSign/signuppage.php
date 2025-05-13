<!DOCTYPE html>
<html lang="en" x-data="signupForm()">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../design/logindesign.css" />
  <title>BoniGlobe Sign Up</title>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body>
  <div class="left-section">
    <img src="../images/turboLogo.png" alt="BoniGlobe Logo" height="90" />
  </div>

  <h3 style="color: aqua;">Join BoniGlobe Today</h3>
  <h1 style="color: white;">Create Your Account</h1>

  <form class="form" @submit.prevent="register">
    <span class="input-span">
      <label for="fullname" class="label">Full Name</label>
      <input type="text" x-model="fullname" required />
    </span>
    <span class="input-span">
      <label for="email" class="label">Email</label>
      <input type="email" x-model="email" required />
    </span>
    <span class="input-span">
      <label for="password" class="label">Password</label>
      <input type="password" x-model="password" required />
    </span>
    <span class="input-span">
      <label for="confirm_password" class="label">Confirm Password</label>
      <input type="password" x-model="confirmPassword" required />
    </span>
    <input class="submit" type="submit" value="Sign Up" />

    <span class="span">Already have an account? <a href="signuppage.php">LOGIN</a> now</span>

    <p x-text="message" style="color: white;"></p>
  </form>

  <script>
  function signupForm() {
    return {
      fullname: '',
      email: '',
      password: '',
      confirmPassword: '',
      message: '',

      async register() {
        //confirm password
        if (this.password !== this.confirmPassword) {
          this.message = 'Passwords do not match.';
          return;
        }
        //an object with the value of the form which then goes to the backend
        const formData = new FormData();
        formData.append('fullname', this.fullname);
        formData.append('email', this.email);
        formData.append('password', this.password);
        formData.append('confirm_password', this.confirmPassword);

        try {
          //send the data to backend
          const response = await fetch('signupbackend.php', {
            method: 'POST', //call post request 
            body: formData //the object to send
          });
          // store the response to result
          const result = await response.json();


          // clear fields on success and redirect to loginpage2.php
          if (result.success) {
            this.fullname = '';
            this.email = '';
            this.password = '';
            this.confirmPassword = '';
            window.location.href = "loginpage2.php";
          } else if (result.error) {
            this.message = result.error;
          }
          //awan lng etuy pang checheck lng mabalin ikkaten
        } catch (error) {
          this.message = 'Error submitting form.';
          console.error(error);
        }
      }
    };
  }
  </script>