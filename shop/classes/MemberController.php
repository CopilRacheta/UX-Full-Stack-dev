<?php

// Class representing a MemberController for interacting with members (customers) in the database
class MemberController {

  // Protected database connection property
  protected $db;

  // Constructor to inject the DatabaseController dependency
  public function __construct(DatabaseController $db) {
    $this->db = $db;
  }

  // Function to get a member by ID
  public function get_member_by_id(int $id) {
    // SQL statement for selecting a member by ID with named parameter
    $sql = "SELECT * FROM Customers WHERE UserID = :id";

    // Prepare arguments for the SQL statement
    $args = ['id' => $id];

    // Execute the SQL statement and return the fetched member (single row)
    return $this->db->runSQL($sql, $args)->fetch();
  }

  // Function to get a member by email
  public function get_member_by_email(string $email) {
    // SQL statement for selecting a member by email with named parameter
    $sql = "SELECT * FROM Customers WHERE Email = :email";

    // Prepare arguments for the SQL statement
    $args = ['email' => $email];

    // Execute the SQL statement and return the fetched member (single row)
    return $this->db->runSQL($sql, $args)->fetch();
  }

  // Function to get all members from the database
  public function get_all_members() {
    // Simple SQL statement to select all members
    $sql = "SELECT * FROM Customers";

    // Execute the SQL statement and return all fetched members (multiple rows)
    return $this->db->runSQL($sql)->fetchAll();
  }

  // Function to update a member's information
  public function update_member(array $member) {
    // SQL statement for updating a member with named parameters (fix typo in WHERE clause)
    $sql = "UPDATE Customers SET Firstname = :firstname, Surname = :lastname, Email = :email, Address = :address WHERE UserID = :id";

    // Execute the SQL statement and check if it was successful
    return $this->db->runSQL($sql, $member)->execute();
  }

  // Function to delete a member by ID
  public function delete_member(int $id) {
    // SQL statement for deleting a member by ID with named parameter
    $sql = "DELETE FROM Customers WHERE UserID = :id";

    // Prepare arguments for the SQL statement
    $args = ['id' => $id];

    // Execute the SQL statement and check if it was successful
    return $this->db->runSQL($sql, $args)->execute();
  }

  // Function to register a new member
  public function register_member(array $member) {
    // Try-catch block to handle potential database exceptions
    try {
      // SQL statement for insertion with named parameters
      $sql = "INSERT INTO Customers (Firstname, Surname, Address, Email, Password, IsAdmin) 
              VALUES (:firstname, :lastname, :address, :email, :password, :isAdmin)";

      // Execute the SQL statement with the member data
      $this->db->runSQL($sql, $member);

      // Assuming you don't need to return the inserted data, this can be simplified:
      // return $this->db->runSQL($sql, $member)->execute();

    } catch (PDOException $e) {
      // Handle specific exception code for duplicate email (could be 23000 or 1062 depending on the database)
      if ($e->getCode() == 23000 || $e->getCode() == 1062) {
        return false; // Indicate registration failure due to duplicate email
      } else {
        // Re-throw other exceptions for further handling
        throw $e;
      }
    }
  }

  // Function to login a member by email and password
  public function login_member(string $email, string $password) {
    // First, try to get the member by email
    $user = $this->get_member_by_email($email);
  
     // If a user is found with that email
     if ($user) {
        // Verify the password using password_verify function
        $auth = password_verify($password, $user['Password']);
        // Return the user data if the password is correct, otherwise return false
        return $auth ? $user : false;
      }
      return false;
    }
}
?>