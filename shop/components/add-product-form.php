<?php

// Include the file containing functions used in this script 
require_once './inc/functions.php';

// Initialize a variable to store any error messages
$message = '';

// Check if the form was submitted using POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Process user input from the form
  $name = InputProcessor::processString($_POST['name'] ?? '');
  $description = InputProcessor::processString($_POST['description'] ?? '');
  $price = InputProcessor::processString($_POST['price'] ?? '');
  $image = InputProcessor::processFile($_FILES['image'] ?? []);

  // Flag to indicate if all inputs are valid
  $valid = $name['valid'] && $description['valid'] && $price['valid'] && $image['valid'];

  // Proceed if all inputs are valid
  if ($valid) {
    // Upload the image using the ImageProcessor class 
    $image['value'] = ImageProcessor::upload($_FILES['image']);

    // Prepare arguments for creating the new product
    $args = [
      'name' => $name['value'],
      'description' => $description['value'],
      'price' => $price['value'],
      'image' => $image['value'],
    ];

    // Create a new product using the controllers->products()->create_product function 
    $id = $controllers->products()->create_product($args);

    // Check if the product was created successfully
    if (!empty($id) && $id > 0) {
      // Redirect the user to the product page with the new product ID
      redirect('product', ['id' => $id]);
    } else {
      // Set an error message if product creation failed
      $message = "Error adding product."; // You can improve this message with specific details from the error
    }
  } else {
    // Set an error message indicating invalid input
    $message = "Please fix the following errors: ";
  }
}

?>


  <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data">
    <section class="vh-100">
      <div class="container py-5 h-75">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-2-strong" style="border-radius: 1rem;">
              <div class="card-body p-5 text-center">
    
                <h3 class="mb-2">Add Product</h3>
                <div class="form-outline mb-4">
                  <input type="text" id="name" name="name" class="form-control form-control-lg" placeholder="Name" required value="<?= htmlspecialchars($name['value'] ?? '') ?>"/>
                  <span class="text-danger"><?= $name['error'] ?? '' ?></span>
                </div>
                
                <div class="form-outline mb-4">
                  <input type="text" id="description" name="description" class="form-control form-control-lg" placeholder="Description" required value="<?= htmlspecialchars($description['value'] ?? '') ?>"/>
                  <span class="text-danger"><?= $description['error'] ?? '' ?></span>
                </div>
    
    
                <div class="form-outline mb-4">
                  <input type="number" id="price" name="price" class="form-control form-control-lg" placeholder="Price" required value="<?= htmlspecialchars($price['value'] ?? '') ?>"/>
                  <span class="text-danger"><?= $price['error'] ?? '' ?></span>
                </div>
    
                <div class="form-outline mb-4">
                  <input type="file" accept="image/*" id="image" name="image" class="form-control form-control-lg" placeholder="Select Image"required />
                </div>
    
                <button class="btn btn-primary btn-lg w-100 mb-4" type="submit">Add Product</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
