<?php

class Controllers {

  // Class representing a central controller for managing other controllers

  protected $db = null;  // Database connection object
  protected $members = null;  // Instance of MemberController (lazy loading)
  protected $products = null;  // Instance of ProductController (lazy loading)
  protected $reviews = null;  // Instance of ReviewController (lazy loading)

  public function __construct() {
    // Constructor to establish a database connection and initialize child controllers

    // **TEMPORARY:** Replace these credentials with your actual database configuration
    $type ='mysql';
    $server = '127.0.0.1';
    $db = 'BroadleighGardens';
    $port = '3306';
    $charset = 'latin1';

    $username = 'root';
    $password = '';
    // **TEMPORARY:**

    // Construct the data source name (DSN) for the PDO connection
    $dsn = "$type:host=$server;dbname=$db;port=$port;charset=$charset";

    try {
      // Create a new DatabaseController instance to establish the connection
      $this->db = new DatabaseController($dsn, $username, $password);
    } catch (PDOException $e) {
      // Re-throw the PDO exception with the original message and code
      throw new PDOException($e->getMessage(), $e->getCode());

      // Uncomment the following line if you want to display the error message during development:
      // echo $e;
    }
  }

  public function members() {
    // Function to access the MemberController instance

    // Lazy loading: create a new MemberController instance if it doesn't exist yet
    if ($this->members === null) {
      $this->members = new MemberController($this->db);
    }

    // Return the existing or newly created MemberController instance
    return $this->members;
  }

  public function products() {
    // Function to access the ProductController instance (similar to members())

    if ($this->products === null) {
      $this->products = new ProductController($this->db);
    }

    return $this->products;
  }

  public function reviews() {
    // Function to access the ReviewController instance (similar to members() and products())

    if ($this->reviews === null) {
      $this->reviews = new ReviewController($this->db);
    }

    return $this->reviews;
  }
}
?>