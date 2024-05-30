<?php

class DatabaseController extends PDO {

  // Class extending PDO to provide a database connection controller

  public function __construct(string $dsn, string $username, string $password, array $options = []) {
    // Constructor to establish a database connection

    // Set default options for PDO connection (error mode, fetch mode, prepared statement emulation)
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];

    // Call the parent class constructor to establish the PDO connection
    parent::__construct($dsn, $username, $password, $options);
  }

  public function runSQL(string $sql, array $args = null) {
    // Function to execute an SQL statement with optional arguments

    // Check if any arguments are provided for the SQL statement
    if (!$args) {
      // If no arguments, simply execute the SQL query
      return $this->query($sql);
    } else {
      // If arguments are provided, prepare the SQL statement for safe execution
      $statement = $this->prepare($sql);

      // Execute the prepared statement with the provided arguments
      $statement->execute($args);

      // Return the prepared statement object for further actions 
      return $statement;
    }
  }

}
?>