<?php
session_start();
require '../functions.php';

if (isset($_SESSION['login'])) {
  header('Location: /');
  exit;
}

if (isset($_POST["register"])) {
  if (register($_POST) > 0) {
    echo "
        <script>
            alert('User berhasil ditambahkan');
        </script>
        ";
    header("Location: login.php");
  } else {
    echo mysqli_error($conn);
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../assets/img/halo.webp">
  <title>OldShop | Register Page</title>
  <!-- Bootstrap CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="d-flex flex-column min-vh-100" data-bs-theme="dark">
  <div class="container my-5">
    <h1 class="mb-4 text-center">Register</h1>
    <form action="" method="post" class="needs-validation border p-2-md p-5 rounded" novalidate>
      <div class="mb-3">
        <label for="firstname" class="form-label">Name</label>
        <div class="input-group">
          <input type="text" name="firstname" id="firstname" class="form-control" placeholder="First Name" required>
          <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Last Name" required>
          <div class="invalid-feedback">
            Please enter your name.
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="username" name="username" id="username" class="form-control" placeholder="Username here" autocomplete="username" required>
        <div class="invalid-feedback">
          Please enter your username.
        </div>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Email here" autocomplete="email" required>
        <div class="invalid-feedback">
          Please enter your email.
        </div>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
        <div class="invalid-feedback">
          Please enter your password.
        </div>
      </div>
      <div class="mb-3">
        <label for="password2" class="form-label">Confirm Password</label>
        <input type="password" name="password2" id="password2" class="form-control" required>
        <div class="invalid-feedback">
          Please confirm your password.
        </div>
      </div>
      <button type="submit" name="register" class="btn btn-primary">Register</button>
      <div class="mt-1">
        <a href="login.php" class="text-secondary">Already have an account? Login here</a>
      </div>
    </form>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <script>
    // Bootstrap form validation
    (function () {
      'use strict'

      var forms = document.querySelectorAll('.needs-validation')

      Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }

            form.classList.add('was-validated')
          }, false)
        })
    })()
  </script>
</body>

</html>