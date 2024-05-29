<?php
require_once './inc/functions.php';

if (isset($_SESSION['user']) && is_numeric($_SESSION['user']['UserID'])) {
    $user = $controllers->members()->get_member_by_id($_SESSION['user']['UserID']);
} else {
    // Handle case where user ID is not available (e.g., redirect to login)
}

// Ensure role is set in session
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = $_SESSION['user']['IsAdmin'] ? 'admin' : 'customer';
}
$reviews = $controllers->reviews()->get_review_by_customer_id($_SESSION['user']['UserID']);

// Check if form is submitted for updating user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Gather form data
    $updated_user = [
        'UserID' => $_POST['user_id'],
        'FirstName' => $_POST['first_name'],
        'LastName' => $_POST['last_name'],
        'Email' => $_POST['email'],
        'Address' => $_POST['address']
    ];
 
    // Call the update_user function
    $controllers->members()->update_member($updated_user);
    // Refresh the page after updating
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
?>
         
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <!-- Display welcome message based on user role -->
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <h2>Welcome Admin</h2>
    <?php else: ?>
        <h2>Welcome to your account</h2>
    <?php endif; ?>
 
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $user['Firstname'] ?> <?= $user['Surname'] ?></h5>
                        <p class="card-text"><strong>Email:</strong> <?= $user['Email'] ?></p>
                        <p class="card-text"><strong>Address:</strong> <?= $user['Address'] ?></p>
                        <!-- Button to toggle update form -->
                        <button class="btn btn-primary" onclick="toggleForm()">Update</button>
                        <!-- Update form -->
                        <form method="post" id="updateForm" style="display: none;">
                            <input type="hidden" name="user_id" value="<?= $user['UserID'] ?>">
                            <label for="first_name">First Name:</label><br>
                            <input type="text" id="first_name" name="first_name" value="<?= $user['Firstname'] ?>"><br>
                            <label for="last_name">Last Name:</label><br>
                            <input type="text" id="last_name" name="last_name" value="<?= $user['Surname'] ?>"><br>
                            <label for="user_name">Username:</label><br>
                            <input type="email" id="email" name="email" value="<?= $user['Email'] ?>"><br>
                            <label for="phone">Phone:</label><br>
                            <input type="text" id="address" name="address" value="<?= $user['Address'] ?>"><br><br>
                            <input type="submit" name="submit" value="Update">
                        </form>
                    </div>
                </div>
            
            </div>
        </div>
    </div>
 
    <script>
        // Function to toggle update form visibility
        function toggleForm() {
            var form = document.getElementById('updateForm');
            if (form.style.display === "none") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }
    </script>
</body>
</html>