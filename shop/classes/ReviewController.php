<?php

class ReviewController {

    protected $db;

    public function __construct(DatabaseController $db)
    {
        $this->db = $db;
    }

    public function create_review(array $product) 
    {
        
        $sql = "INSERT INTO Reviews(customer_id, product_id, Reviews)
        VALUES (:name, :description, :price, :image);";
        $this->db->runSQL($sql, $product);
        return $this->db->lastInsertId();
    }

    public function get_review_by_product_id(int $id)
    {
        $sql = "SELECT * FROM Reviews WHERE product_id = :id";
        $args = ['id' => $id];
        return $this->db->runSQL($sql, $args)->fetch();
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