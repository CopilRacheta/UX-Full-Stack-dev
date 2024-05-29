<?php

// Include the file containing functions used in this script (likely for processing user input and interacting with the database)
require_once './inc/functions.php';

// Initialize a variable to store any error message retrieved from the URL with proper sanitization
$message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';

// Check if the form was submitted using POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Process user input from the registration form
  $fname = InputProcessor::processString($_POST['fname']); // Process first name with validation
  $sname = InputProcessor::processString($_POST['sname']); // Process last name with validation
  $email = InputProcessor::processEmail($_POST['email']); // Process email with validation
  $password = InputProcessor::processPassword($_POST['password'], $_POST['password-v']); // Process password with validation (including confirmation)
  $address = InputProcessor::processString($_POST['address']); // Process address with validation
  $isAdmin = isset($_POST['isAdmin']) ? 1 : 0; // Check if "isAdmin" checkbox is checked (set to 1) or not (set to 0)

  // Flag to indicate if all inputs are valid
  $valid = $fname['valid'] && $sname['valid'] && $address['valid'] && $email['valid'] && $password['valid'];

  // Set an error message if any input is invalid
  $message = !$valid ? "Please fix the above errors:" : '';

  // Proceed if all inputs are valid
  if ($valid) {
    // Prepare arguments for creating the new member
    $args = [
      'firstname' => $fname['value'],
      'lastname' => $sname['value'],
      'address' => $address['value'],
      'email' => $email['value'],
      'password' => password_hash($password['value'], PASSWORD_DEFAULT), // Hash the password securely
      'isAdmin' => $isAdmin, // Include the new field for admin status
    ];

    // Register the new member using the controllers->members()->register_member function (replace with actual call)
    $member = $controllers->members()->register_member($args);

    // Check if registration was successful
    if ($member) {
      // Redirect the user to the login page with a message suggesting to login with the new account
      redirect("login", ["error" => "Please login with your new account"]);
    } else {
      // Set an error message if email is already registered
      $message = "Email already registered.";
    }
  }
}

?>

<form method="post" action=" <?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
  <section class="vh-100">
    <div class="container py-5 h-75">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card shadow-2-strong" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">

              <h3 class="mb-2">Register</h3>
              <div class="form-outline mb-4">
                <input required type="text" id="fname" name="fname" class="form-control form-control-lg" placeholder="Firstname" value="<?= htmlspecialchars($fname['value'] ?? '') ?>"/>
                <small class="text-danger"><?= htmlspecialchars($fname['error'] ?? '') ?></small>
              </div>

              <div class="form-outline mb-4">
                <input required type="text" id="sname" name="sname" class="form-control form-control-lg" placeholder="Surname" value="<?= htmlspecialchars($sname['value'] ?? '') ?>"/>
                <small class="text-danger"><?= htmlspecialchars($sname['error'] ?? '') ?></small>
              </div>

              <div class="form-outline mb-4">
                <input required type="text" id="address" name="address" class="form-control form-control-lg" placeholder="Address" value="<?= htmlspecialchars($address['value'] ?? '') ?>"/>
                <small class="text-danger"><?= htmlspecialchars($address['error'] ?? '') ?></small>
              </div>

              <div class="form-outline mb-4">
                <input required type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email" value="<?= htmlspecialchars($email['value']?? '') ?>" />
                <small class="text-danger"><?= htmlspecialchars($email['error'] ?? '') ?></small>
              </div>

              <div class="form-outline mb-4">
                <input required type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Password" />
              </div>
              
              <div class="form-outline mb-4">
                <input required type="password" id="password-v" name="password-v" class="form-control form-control-lg" placeholder="Password again" />
                <small class="text-danger"><?= htmlspecialchars($password['error'] ?? '') ?></small>
              </div>

              <div class="form-check mb-4">
  <input class="form-check-input" type="checkbox" value="1" id="isAdmin" name="isAdmin">
  <label class="form-check-label" for="isAdmin">
    Is Admin?
  </label>
</div>

              <button class="btn btn-primary btn-lg w-100 mb-4" type="submit">Register</button>
              <a class="btn btn-secondary btn-lg w-100" type="submit" href="./login.php" >Already got an account?</a>

              <?php if ($message): ?>
                <div class="alert alert-danger mt-4">
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