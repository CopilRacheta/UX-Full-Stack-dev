<?php

// Unset all session variables (effectively logging out the user)
session_unset(); 

// Include the file containing functions used in this script (likely for processing user input and interacting with the database)
require_once './inc/functions.php';

// Initialize variables to store user input and error message
$message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : ''; // Get and sanitize potential error message from URL
$email = null;
$password = null;

// Check if the form was submitted using POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Process user input from the login form
  $email = InputProcessor::processEmail($_POST['email']); // Process email with validation
  $password = InputProcessor::processPassword($_POST['password']); // Process password with validation

  // Flag to indicate if both inputs are valid
  $valid = $email['valid'] && $password['valid'];

  // Proceed if both email and password are valid
  if ($valid) {
    // Attempt to login the user using the controllers->members()->login_member function (replace with actual call)
    $user = $controllers->members()->login_member($email['value'], $password['value']);

    // Check if login was successful
    if (!$user) {
      // Set an error message if login failed
      $message = "User details are incorrect."; 
    } else {
      // Store user data in the session and redirect to the member page
      $_SESSION['user'] = $user;
      redirect('member');
    }
  } else {
    // Set an error message indicating invalid input
    $message = "Please fix the above errors. ";
  }
}

?>

<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
  <section class="vh-100">
    <div class="container py-5 h-75">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card shadow-2-strong" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">
  
              <h3 class="mb-2">Sign in</h3>
              <div class="form-outline mb-4">
                <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email" required value="<?= htmlspecialchars($email['value'] ?? '') ?>"/>
                  <span class="text-danger"><?= $email['error'] ?? '' ?></span>
                </div>
  
              <div class="form-outline mb-4">
                <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Password" required value="<?= htmlspecialchars($password['value'] ?? '') ?>"/>
                  <span class="text-danger"><?= $password['error'] ?? '' ?></span>
                </div>
  
              <button class="btn btn-primary btn-lg w-100 mb-4" type="submit">Login</button>
              <a class="btn btn-secondary btn-lg w-100" type="submit" href="./register.php" >Not got an account?</a>
              
              <?php if ($message): ?>
                <div class="alert alert-danger mt-4" role="alert">
                  <?= $message ?? '' ?>
                </div>
              <?php endif ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</form>