<?php

// Include the file containing functions used in this script
require_once './inc/functions.php';

// Get all products from the database using the controllers->products()->get_all_products function 
$products = $controllers->products()->get_all_products();

// Loop through each product in the fetched results
foreach ($products as $product): ?>

  <div class="col-4">
    <div class="card">
      <img src="<?= $product['Image'] ?>" class="card-img-top" alt="image of <?= $product['Description'] ?>">
      <div class="card-body">
        <h5 class="card-title"><?= $product['ProductName'] ?></h5>
        <p class="card-text"><?= $product['Description'] ?></p>
        <p class="card-text">£<?= $product['Price'] ?></p>
      </div>
    </div>
  </div>

<?php 
endforeach;
?>