<?php
session_start();
$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['IsAdmin'];
$isLoggedIn = isset($_SESSION['user']);
 
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ./index.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title> <?= $title ?? 'Welcome' ?> </title>
    <style>
        .bg-light-green {
            background-color: #B6FFDD; /* Light green color */
            
        }

        .bg-green {
            background-color: #00FF48; /* Light green color */
            
        }
        body * {
  color: black;
}
h1, h2, h3, h4, h5, h6 {
  color: black;
}

a {
  color: black;
}
.nav-link {
  color: black; /* Change to your desired color */
  padding-left: 10px; /* Adjust the pixel value for desired spacing */
  padding-right: 10px; /* Consider adding padding-right for consistency */
}
#myNav .nav-link {  /* Targeting the nav-link class within the element with ID "myNav" */
  margin-right: 0 !important;  /* Override any right margin for consistency */
  padding-left: 10px;
  padding-right: 10px;
}

    </style>
  </head>
  <body class="bg-light-green">
 
   <nav class="navbar navbar-expand-lg bg-green">
      <div class="container-fluid">
        <a class="navbar-brand" href="./index.php">Broadleigh Gardens</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
          <ul class="navbar-nav mb-2 mb-lg-0">
          <li class="nav-item">
              <a class="nav-link" href="./product.php">Shop</a>
            </li>
            <?php if (!$isLoggedIn): ?>
              <li class="nav-item">
                <a class="nav-link" href="./register.php">Register</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./login.php">Login</a>
              </li>
            <?php endif; ?>
           
            <?php if ($isLoggedIn): ?>
              <li class="nav-item">
                <a class="nav-link" href="./review.php">Review</a>
              </li>
            <?php endif; ?>
            <?php if ($isAdmin): ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Admin</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="./admin-pannel.php">Admin Panel</a></li>
                  <li><a class="dropdown-item" href="./add-product.php">Add Product</a></li>
                </ul>
              </li>
            <?php endif; ?>
            <?php if ($isLoggedIn): ?>


              <li class="nav-item">
                <a class="nav-link" href="?logout=true">Logout</a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="./member.php"><i class="bi bi-person-circle" style="font-size: 2rem;"></i></a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>

