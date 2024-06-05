<?php
session_start();
include 'database/app.php';
// Check for cookies
if (isset($_COOKIE['lmp']) && isset($_COOKIE['lock'])) {
  $id = $_COOKIE['lmp'];
  $key = $_COOKIE['lock'];
  
  // Retrieve username based on id
  $result = mysqli_query($conn,"SELECT username,nama,level FROM akun WHERE id_akun=$id");
  $row = mysqli_fetch_assoc($result);

  // Validate cookie and username
  if($key === hash('sha512/224', $row['username'])){
    $_SESSION['login'] = true;
    $_SESSION['id_akun'] = $row['id_akun'];
    $_SESSION['nama'] = $row['nama'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['level'] = $row['level'];
  }
}
if (isset($_SESSION['login'])) {
  header("location: index.php");
  exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM akun WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['id_akun'] = $row['id_akun'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['level'] = $row['level'];

            // Check for remember me
            if(isset($_POST['remember'])){
              // Create cookies
              setcookie('lmp', $row['id_akun'], time() + 60); // 1 jam 3600  24 jam 86400
              setcookie('lock', hash('sha512/224', $row['username']), time() + 60);
            }
            header("Location: index.php");
            exit;
        }
    }
    $error = true;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>Admin Login</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/sign-in/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="/docs/5.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link rel="icon" href="assets/img/astb.png">
    <meta name="theme-color" content="#712cf9">
    
    <!-- Custom styles for this template -->
    <link href="assets/compiled/css/style.css" rel="stylesheet">
  </head>
  <body class="text-center">
    
<main class="form-signin w-100 m-auto">
  <form action="" method="post">
    <img class="mb-4" src="assets/compiled/png/astb.png" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Admin Login</h1>
    <?php if (isset($error)): ?>
    <div class="alert alert-danger text-center">
      <b>Username/Password SALAH</b>
    </div>
    <?php endif; ?>
    <div class="form-floating">
      <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username" autocomplete="off" required >
      <label for="floatingInput">Username</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="floatingPassword" placeholder="Password.." name="password" required>
      <label for="floatingPassword">Password</label>
    </div>
    <div class="form-check text-start my-3">
      <input class="form-check-input" type="checkbox" value="remember-me" name="remember" id="flexCheckDefault">
      <label class="form-check-label" for="flexCheckDefault">
        Remember me
      </label>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Login</button>
  </form>
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
