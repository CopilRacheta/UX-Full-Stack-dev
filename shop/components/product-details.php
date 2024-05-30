<?php

// Include the file containing functions used in this script 
require_once './inc/functions.php';

// Get the product ID from the URL parameter (if it exists)
$id = $_GET['id'] ?? '';

// Check if a product ID is provided
if (!empty($id)) {
  // Fetch product details from the database using the controllers->products()->get_product_by_id function 
  $product = $controllers->products()->get_product_by_id($id);

  // Check if the product was found
  if ($product): ?>

    <div class="card" style="width: 18rem;">
      <img src="<?= $product['Image'] ?>" class="card-img-top" alt="image of <?= $product['Description'] ?>">
      <div class="card-body">
        <h5 class="card-title"><?= $product['ProductName'] ?></h5>
        <p class="card-text"><?= $product['Description'] ?></p>
        <p class="card-text"><?= $product['Price'] ?></p>
      </div>
    </div>

  <?php 
  else:
    // Redirect to a "not found" page if the product is not found
    redirect("not-found"); 
  endif;
} else {
  // Redirect to a "not found" page if no product ID is provided
  redirect("not-found"); 
}

?>