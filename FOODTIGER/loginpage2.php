<!DOCTYPE html>
<html lang="en" x-data="loginForm()">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="design/logindesign.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <title>BoniGlobe Sign in</title>
</head>

<body>

  <div class="">
    <img src="images/turboLogo.png" alt="BoniGlobe Logo" height="90">
  </div>

  <h3 id="text1" class="Texts">Before anything else</h3>
  <h1 id="text2" class="Texts">Sign In to BoniGlobe Today!</h1>

  <form class="form" @submit.prevent="submit">
    <span class="input-span">
      <label for="email" class="label">Email</label>
      <input type="email" id="email" x-model="email" required />
    </span>
    <span class="input-span">
      <label for="password" class="label">Password</label>
      <input type="password" id="password" x-model="password" required />
    </span>
    <input class="submit" type="submit" value="Log in" />
    <p x-text="message" style="color: red;"></p>
    <span class="span">Don't have an account? <a href="signuppage.php">Register</a> now</span>
  </form>

  <script>
  function loginForm() {
    return {
      email: '',
      password: '',
      async submit() {
        const formData = new FormData();
        formData.append('email', this.email);
        formData.append('password', this.password);

        try {
          const response = await fetch('loginbackend.php', {
            method: 'POST',
            body: formData
          });

          const result = await response.json();
          this.message = result;

          // Optional: clear fields on success
          if (result.success) {
            this.email = '';
            this.password = '';
            window.location.href = "mainmenu.php";
          } else if (result.error) {
            this.message = result.error;
          }
        } catch (error) {
          this.message = 'Error submitting form.';
          console.error(error);
        }
      }
    }
  }
  </script>

</body>

</html>