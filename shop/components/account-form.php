<?php
// Include the file containing functions used in this script 
require_once './inc/functions.php';

// Check if a user session is set and if the UserID in the session is numeric 
if (isset($_SESSION['user']) && is_numeric($_SESSION['user']['UserID'])) {
  // If valid, fetch the user data using their UserID 
  $user = $controllers->members()->get_member_by_id($_SESSION['user']['UserID']);
} else {
    redirect('login', ["error" => "You need to be logged in to view this page"]);
}

// Ensure role (admin/customer) is set in session
if (!isset($_SESSION['role'])) {
  // Assign the role based on the IsAdmin value in the user session
  $_SESSION['role'] = $_SESSION['user']['IsAdmin'] ? 'admin' : 'customer';
}

// Fetch reviews for the current user using their UserID from the session
$reviews = $controllers->reviews()->get_review_by_customer_id($_SESSION['user']['UserID']);

// Check if the form is submitted for updating user information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && isset($_POST['user_id'])) {
    // Gather form data
    $updated_user = [
        'UserID' => $_POST['user_id'],
        'FirstName' => $_POST['first_name'],
        'LastName' => $_POST['last_name'],
        'Email' => $_POST['email'],
        'Address' => $_POST['address']
    ];


  // Call the update_user function to update user data in the database 
  $controllers->members()->update_member($updated_user);

  // Redirect the user back to the current page after successful update
  header("Location: {$_SERVER['PHP_SELF']}");
  exit();
}

// Check if a user deletion form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']) && isset($_POST['user_id'])) {
    // Get the user ID to be deleted
    $user_id = $_POST['user_id'];
  
    // Delete the user from the database
    $controllers->members()->delete_member($user_id);
  
    // Redirect back to the current page after deletion
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
  }
?>
         
         <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
</head>
<body>
    <h1>Customer Accounts</h1>
    <div class="container mt-5">
        <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h2>User Account</h2>
                            <h5 class="card-title"><?= $user['Firstname'] ?> <?= $user['Surname'] ?></h5>
                            <p class="card-text"><strong>Email:</strong> <?= $user['Email'] ?></p>
                            <p class="card-text"><strong>Address:</strong> <?= $user['Address'] ?></p>
                            <button class="btn btn-primary" onclick="toggleForm(<?= $user['UserID'] ?>)">Update</button>
                            <form method="post" id="updateForm_<?= $user['UserID'] ?>" style="display: none;">
                                <input type="hidden" name="user_id" value="<?= $user['UserID'] ?>">
                                <label for="first_name">First Name:</label><br>
                                <input type="text" id="first_name" name="first_name" value="<?= $user['Firstname'] ?>"><br>
                                <label for="last_name">Last Name:</label><br>
                                <input type="text" id="last_name" name="last_name" value="<?= $user['Surname'] ?>"><br>
                                <label for="email">Email:</label><br>
                                <input type="email" id="email" name="email" value="<?= $user['Email'] ?>"><br>
                                <label for="address">Address:</label><br>
                                <input type="text" id="address" name="address" value="<?= $user['Address'] ?>"><br><br>
                                <input type="submit" name="submit" value="Update">
                            </form>
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this account?');">
                                <input type="hidden" name="user_id" value="<?= $user['UserID'] ?>">
                                <button type="submit" class="btn btn-danger" name="delete">Delete Account</button>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
 
    <script>
    // Function to toggle update form visibility for users
    function toggleForm(userId) {
        var form = document.getElementById('updateForm_' + userId);
        form.style.display = form.style.display === "none" ? "block" : "none";
    }
 
    // Function to toggle update form visibility for products
    function toggleProductForm(productId) {
        var form = document.getElementById('productForm_' + productId);
        form.style.display = form.style.display === "none" ? "block" : "none";
    }
    </script>
</body>
</html>