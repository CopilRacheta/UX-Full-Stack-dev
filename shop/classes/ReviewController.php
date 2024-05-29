<?php

// Class representing a ReviewController for interacting with reviews in the database
class ReviewController {

  // Protected database connection property
  protected $db;

  // Constructor to inject the DatabaseController dependency
  public function __construct(DatabaseController $db) {
    $this->db = $db;
  }

  // Function to create a new review in the database
  public function create_review(array $review) {
    // SQL statement for insertion with named parameters
    $sql = "INSERT INTO Reviews(UserID, Review)
             VALUES (:UserID,:Review);";

    // Execute the SQL statement with the review data and get the last inserted ID
    $this->db->runSQL($sql, $review);
    return $this->db->lastInsertId();
  }

  // Function to get a review by customer ID
  public function get_review_by_customer_id(int $userid) {
    // SQL statement for selecting a review by user ID with named parameter
    $sql = "SELECT * FROM Reviews WHERE UserID = :userid";

    // Prepare arguments for the SQL statement
    $args = ['userid' => $userid];

    // Execute the SQL statement and return the fetched review (single row)
    return $this->db->runSQL($sql, $args)->fetch();
  }

  // Function to get all reviews with associated customer names
  public function get_all_reviews_with_customer_name() {
    // SQL statement for selecting all reviews with customer first names from joined tables
    $sql = "SELECT reviews.*, customers.firstname FROM reviews
             JOIN customers ON reviews.UserID = customers.UserID";

    // Execute the SQL statement and return all fetched reviews (multiple rows)
    return $this->db->runSQL($sql)->fetchAll();
  }

  // Function to get all product reviews (replace with specific logic if needed)
  public function get_all_products_reviews() {
    // Simple SQL statement to select all reviews (replace with specific logic if needed)
    $sql = "SELECT * FROM Reviews";

    // Execute the SQL statement and return all fetched reviews (multiple rows)
    return $this->db->runSQL($sql)->fetchAll();
  }

  // Function to delete a review by ID
  public function delete_review(int $id) {
    // SQL statement for deleting a review by ID with named parameter
    $sql = "DELETE FROM Reviews WHERE reviews_id = :id";

    // Prepare arguments for the SQL statement
    $args = ['id' => $id];

    // Execute the SQL statement and check if it was successful
    return $this->db->runSQL($sql, $args)->execute();
  }

}

?>