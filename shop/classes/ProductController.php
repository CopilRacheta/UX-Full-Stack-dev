<?php

// Class representing a ProductController for interacting with products in the database
class ProductController {

  // Protected database connection property
  protected $db;

  // Constructor to inject the DatabaseController dependency
  public function __construct(DatabaseController $db) {
    $this->db = $db;
  }

  // Function to create a new product in the database
  public function create_product(array $product) {
    // SQL statement for insertion with named parameters
    $sql = "INSERT INTO Products(ProductName, Description, Price, Image)
             VALUES (:name, :description, :price, :image);";

    // Execute the SQL statement with the product data and get the last inserted ID
    $this->db->runSQL($sql, $product);
    return $this->db->lastInsertId();
  }

  // Function to get a product by ID
  public function get_product_by_id(int $id) {
    // SQL statement for selecting a product by ID with named parameter
    $sql = "SELECT * FROM Products WHERE product_id = :id";

    // Prepare arguments for the SQL statement
    $args = ['id' => $id];

    // Execute the SQL statement and return the fetched product (single row)
    return $this->db->runSQL($sql, $args)->fetch();
  }

  // Function to get all products from the database
  public function get_all_products() {
    // Simple SQL statement to select all products
    $sql = "SELECT * FROM Products";

    // Execute the SQL statement and return all fetched products (multiple rows)
    return $this->db->runSQL($sql)->fetchAll();
  }

  // Function to update a product in the database
  public function update_product(array $product) {
    // SQL statement for updating a product with named parameters
    $sql = "UPDATE Products SET ProductName = :name, Description = :description, Price = :price WHERE `product_id` = :id";

    // **Important:** Ensure all keys in $product match placeholders in the SQL string (case-sensitive)
    // This comment highlights the importance of matching parameter names in the product array with the placeholders in the SQL statement.

    // Execute the SQL statement and check if it was successful
    return $this->db->runSQL($sql, $product)->execute();
  }

  // Function to delete a product by ID
  public function delete_product(int $id) {
    // SQL statement for deleting a product by ID with named parameter
    $sql = "DELETE FROM Products WHERE product_id = :id";

    // Prepare arguments for the SQL statement
    $args = ['id' => $id];

    // Execute the SQL statement and check if it was successful
    return $this->db->runSQL($sql, $args)->execute();
  }

}

?>