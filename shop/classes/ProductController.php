<?php

class ProductController {

    protected $db;

    public function __construct(DatabaseController $db)
    {
        $this->db = $db;
    }

    public function create_product(array $product) 
    {
        
        $sql = "INSERT INTO Products(ProductName, Description, Price, Image)
        VALUES (:name, :description, :price, :image);";
        $this->db->runSQL($sql, $product);
        return $this->db->lastInsertId();
    }

    public function get_product_by_id(int $id)
    {
        $sql = "SELECT * FROM Products WHERE product_id = :id";
        $args = ['id' => $id];
        return $this->db->runSQL($sql, $args)->fetch();
    }

    public function get_all_products()
    {
        $sql = "SELECT * FROM Products";
        return $this->db->runSQL($sql)->fetchAll();
    }

    public function update_product(array $product)
    {
        $sql = "UPDATE Products SET ProductName = :name, Description = :description, Price = :price, Image = :image WHERE product_id = :id";
        return $this->db->runSQL($sql, $product)->execute();
    }

    public function delete_product(int $id)
    {
        $sql = "DELETE FROM Products WHERE product_id = :id";
        $args = ['id' => $id];
        return $this->db->runSQL($sql, $args)->execute();
    }

}

?>