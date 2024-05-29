<?php

class ReviewController {

    protected $db;

    public function __construct(DatabaseController $db)
    {
        $this->db = $db;
    }

    public function create_review(array $review) 
    {
        
        $sql = "INSERT INTO Reviews(UserID,  Review)
        VALUES (:UserID,:Review);";
        $this->db->runSQL($sql, $review);
        return $this->db->lastInsertId();
    }

    public function get_review_by_customer_id(int $userid)
    {
        $sql = "SELECT * FROM Reviews WHERE UserID = :userid";
        $args = ['userid' => $userid];
        return $this->db->runSQL($sql, $args)->fetch();
    }

    public function get_all_reviews_with_customer_name()
    {
        $sql = "SELECT reviews.*, customers.firstname FROM reviews
                JOIN customers ON reviews.UserID = customers.UserID";
        return $this->db->runSQL($sql)->fetchAll();
    }

    public function get_all_products_reviews()
    {
        $sql = "SELECT * FROM Reviews";
        return $this->db->runSQL($sql)->fetchAll();
    }

    public function delete_review(int $id)
    {
        $sql = "DELETE FROM Reviews WHERE reviews_id = :id";
        $args = ['id' => $id];
        return $this->db->runSQL($sql, $args)->execute();
    }

}

?>