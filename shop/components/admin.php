<?php
require_once './inc/functions.php';
 
// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to the login page if the user is not logged in
    redirect('login', ["error" => "You need to be logged in to view this page"]);
}
 
// Ensure role is set in session
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = $_SESSION['user']['IsAdmin'] ? 'admin' : 'customer';
}
 
// Fetch all users
$users = $controllers->members()->get_all_members();
 
// Fetch all products
$products = $controllers->products()->get_all_products();
 
// Fetch all reviews with user email
$reviews = $controllers->reviews()->get_all_reviews_with_customer_name();  // Updated method call
 
// Check if form is submitted for updating user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && isset($_POST['user_id'])) {
    // Gather form data
    $updated_user = [
        'firstname' => $_POST['first_name'],
        'lastname' => $_POST['last_name'],
        'email' => $_POST['email'],
        'address' => $_POST['address']
    ];
   
    // Call the update_user function
    $controllers->members()->update_member($updated_user);
    // Refresh the page after updating
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
 
// Check if form is submitted for deleting user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']) && isset($_POST['user_id'])) {
    // Gather user ID to delete
    $user_id = $_POST['user_id'];
    // Call the delete_user function
    $controllers->members()->delete_member($user_id);
    // Refresh the page after deletion
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
 
// Check if form is submitted for updating product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && isset($_POST['product_id'])) {
    // Gather form data
    $product = [
        'ProductID' => $_POST['product_id'],
        'ProductName' => $_POST['name'],
        'Description' => $_POST['description'],
        'Price' => $_POST['price']
    ];
 
    // Call the update_product function
    $controllers->products()->update_product($product);
    // Refresh the page after updating
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
 
// Check if form is submitted for deleting product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product']) && isset($_POST['product_id'])) {
    // Gather product ID to delete
    $product_id = $_POST['product_id'];
    // Call the delete_product function
    $controllers->products()->delete_product($product_id);
    // Refresh the page after deletion
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
 
// Check if form is submitted for deleting review
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_review']) && isset($_POST['review_id']) && isset($_POST['user_id'])) {
    // Gather review ID and user ID to delete
    $review_id = $_POST['review_id'];
    $user_id = $_POST['user_id'];
    // Call the delete_review function
    $controllers->reviews()->delete_review($review_id, $user_id);
    // Refresh the page after deletion
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
            <?php foreach ($users as $user): ?>
                <div class="col-md-6">
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
            <?php endforeach; ?>
        </div>
    </div>
 
    <h1>Products for sale</h1>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <?php foreach ($products as $product): ?>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h2>Product Details</h2>
                            <img src="<?= $product['Image'] ?>" class="card-img-top" alt="image of <?= $product['Description'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $product['ProductName'] ?></h5>
                                <p class="card-text"><?= $product['Description'] ?></p>
                                <p class="card-text"><?= $product['Price'] ?></p>
                                <button class="btn btn-primary" onclick="toggleProductForm(<?= $product['product_id'] ?>)">Update</button>
                                <form method="post" id="productForm_<?= $product['product_id'] ?>" style="display: none;">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <label for="name">Name:</label><br>
                                    <input type="text" id="name" name="name" value="<?= $product['ProductName'] ?>"><br>
                                    <label for="description">Description:</label><br>
                                    <textarea id="description" name="description"><?= $product['Description'] ?></textarea><br>
                                    <label for="price">Price:</label><br>
                                    <input type="number" id="price" name="price" value="<?= $product['Price'] ?>" step="0.01"><br><br>
                                    <input type="submit" name="submit" value="Update">
                                </form>
                                <form method="post" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <button type="submit" class="btn btn-danger" name="delete_product">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
 
    <h1>All Reviews</h1>
    <div class="container mt-5">
    <?php
    // Group reviews by email
    $grouped_reviews = [];
    foreach ($reviews as $review) {
        $email = $review['firstname'];
        if (!isset($grouped_reviews[$email])) {
            $grouped_reviews[$email] = [];
        }
        $grouped_reviews[$email][] = $review;
    }
    ?>
    <div class="row justify-content-center">
        <?php foreach ($grouped_reviews as $email => $reviews_by_email): ?>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h2>Reviews by <?= htmlspecialchars($email) ?></h2>
                        <?php foreach ($reviews_by_email as $review): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <p class="card-text"><?= htmlspecialchars($review['Review']) ?></p>
                                    <form method="post" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        <input type="hidden" name="review_id" value="<?= $review['reviews_id'] ?>">
                                        <input type="hidden" name="user_id" value="<?= $review['UserID'] ?>">
                                        <button type="submit" class="btn btn-danger" name="delete_review">Delete Review</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
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