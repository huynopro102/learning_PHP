<?php
class AccountModel
{
    private $conn;
    private $table_name = "account";
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getAccountByUsername($username)
    {
        $query = "SELECT * FROM account WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
   


    function save($username, $fullname, $password, $role_id = 5, $google_id = null)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, fullname, password, role_id, google_id) 
                  VALUES (:username, :fullname, :password, :role_id, :google_id)";
        
        $stmt = $this->conn->prepare($query);
    
        // Clean and sanitize data
        $username = htmlspecialchars(strip_tags($username));
        $fullname = htmlspecialchars(strip_tags($fullname));
        
        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
        $stmt->bindParam(':google_id', $google_id, PDO::PARAM_STR);
        
        // Execute and return result
        return $stmt->execute();
    }
    



}
