<?php

class MemberController {

    protected $db;

    public function __construct(DatabaseController $db)
    {
        $this->db = $db;
    }

    public function get_member_by_id(int $id)
    {
        $sql = "SELECT * FROM Customers WHERE customer_id = :id";
        $args = ['id' => $id];
        return $this->db->runSQL($sql, $args)->fetch();
    }

    public function get_member_by_email(string $email)
    {
        $sql = "SELECT * FROM Customers WHERE Email = :email";
        $args = ['email' => $email];
        return $this->db->runSQL($sql, $args)->fetch();
    }

    public function get_all_members()
    {
        $sql = "SELECT * FROM Customers";
        return $this->db->runSQL($sql)->fetchAll();
    }

    public function update_member(array $member)
    {
        $sql = "UPDATE Customers SET Firstname = :firstname, Surname = :lastname, Email = :email WHERE customer_id = :id";
        return $this->db->runSQL($sql, $member)->execute();
    }

    public function delete_member(int $id)
    {
        $sql = "DELETE FROM Customers WHERE customer_id = :id";
        $args = ['id' => $id];
        return $this->db->runSQL($sql, $args)->execute();
    }

    public function register_member(array $member)
    {
        try {

            $sql = "INSERT INTO Customers(Firstname, Surname,Address, Email, Password) 
                    VALUES (:firstname, :lastname,:address, :email, :password)"; 

            return $this->db->runSQL($sql, $member)->fetch();

        } catch (PDOException $e) {

            if ($e->getCode() == 23000) { //Could be 1062
                return false;
            }
            throw $e;
        }
    }   

    public function login_member(string $email, string $password)
    {
        $member = $this->get_member_by_email($email);

        if ($member) {
            $auth = password_verify($password,  $member['Password']);
            return $auth ? $member : false;
        }
        return false;
    }


}

?>