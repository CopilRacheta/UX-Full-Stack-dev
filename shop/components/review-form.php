<?php
require_once './inc/functions.php';

$user = $controllers->members()->get_member_by_id($_SESSION['user']['UserID']);
 
// Check if form is submitted for leaving a review
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    // Gather form data
    $review_data = [
        'UserID' => $_POST['user_id'],
        'Review' => $_POST['review']
    ];
        // Call the create_review function
        $controllers->reviews()->create_review($review_data);
        // Set the popup message
        $popup_message = "Review created successfully!";
  }

  // Fetch all reviews with user email
$reviews = $controllers->reviews()->get_all_reviews_with_customer_name();  // Updated method call


// Check if form is submitted for updating a review
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_review'])) {
    // Gather form data
    $updated_review = [
        'id' => $_POST['review_id'],
        'Review' => $_POST['review'],
        'user_id' => $_SESSION['user']['UserID']
    ];
 
    // Call the update_review function
    $controllers->reviews()->update_review($updated_review);
    // Refresh the page after updating the review
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
// Check if form is submitted for deleting a review
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_review'])) {
    // Gather form data
    $review_id = $_POST['review_id'];
    $user_id = $_SESSION['user']['UserID'];
 
    // Call the delete_review function
    $controllers->reviews()->delete_review($review_id, $user_id);
    // Refresh the page after deleting the review
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <!-- Add the review form here -->
                    <h3 class="text-center mb-4">Leave a Review</h3>
                    <form method="post" action="">
                        <input type="hidden" name="user_id" value="<?= $user['UserID'] ?>">
                        <div class="mb-3">
                            <label for="review" class="form-label">Your Review:</label>
                            <textarea id="review" name="review" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="submit_review" class="btn btn-primary">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     <!-- Display user reviews -->
  <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Your Reviews</h5>
                        <?php if (count($reviews) > 0): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($reviews as $review): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="review-text">
                                            <?= htmlspecialchars($review['Review']) ?>
                                        </div>
                                        <div class="review-actions">
                                            <!-- Button to toggle review update form -->
                                            <button class="btn btn-secondary btn-sm" onclick="toggleReviewForm(<?= $review['reviews_id'] ?>)">Edit</button>
                                            <!-- Delete review form -->
                                            <form method="post" action="" style="display:inline;">
                                                <input type="hidden" name="review_id" value="<?= $review['reviews_id'] ?>">
                                                <button type="submit" name="delete_review" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this review?');">Delete</button>
                                            </form>
                                        </div>
                                    </li>
                                    <!-- Review update form -->
                                    <li class="list-group-item review-update-form" id="updateReviewForm-<?= $review['reviews_id'] ?>" style="display: none;">
                                        <form method="post">
                                            <input type="hidden" name="review_id" value="<?= $review['reviews_id'] ?>">
                                            <div class="form-group">
                                                <label for="review">Edit Review:</label>
                                                <textarea class="form-control" id="review" name="review" rows="4" cols="50"><?= htmlspecialchars($review['Review']) ?></textarea>
                                            </div>
                                            <div class="form-group text-end">
                                                <button type="submit" name="update_review" class="btn btn-primary btn-sm">Update Review</button>
                                            </div>
                                        </form>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>You haven't left any reviews yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
</div>
 
 
    <script>
 
        // Function to toggle review update form visibility
        function toggleReviewForm(reviewId) {
            var form = document.getElementById('updateReviewForm-' + reviewId);
            if (form.style.display === "none") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }
    </script>
</body>
</html>
