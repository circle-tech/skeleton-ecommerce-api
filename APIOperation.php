<?php

class APIOperation {
    
    private $con;

    function __construct() {
        require_once 'Connect.php';

        $db = new Connect();
        $this->con = $db->connect();
    }

    function registerAcc($username, $email, $password) {
        $stmt = $this->con->prepare("INSERT INTO account (userName, email, password) VALUES (?, ?, ?)");
        $password = md5($password);
        $stmt->bind_param("sss", $username, $email, $password);
        if($stmt->execute())
            return true;
        return false; 
    }

    function loginAcc($username, $password) {
        $stmt = $this->con->prepare("SELECT * FROM account WHERE userName=? AND password=?");
        $password = md5($password);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1)
            return true;
        return false;
    }

    function getAcc($username) {
        $stmt = $this->con->prepare("SELECT userName, email FROM account WHERE userName=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($username, $email);

        $accounts = array(); 

        while($stmt->fetch()) {
            $acc  = array();
            $acc['userName'] = $username; 
            $acc['email'] = $email;

            array_push($accounts, $acc);
        }
        return $accounts; 
    }

    function getProducts($minquantity) {
        $stmt = $this->con->prepare("SELECT productId, productName, productDescription, productQuantity, productPrice, productImage FROM product WHERE productQuantity > ?");
        $stmt->bind_param("i", $minquantity);
        $stmt->execute();
        $stmt->bind_result($productid, $productname, $productdesc, $productquantity, $productprice, $productimage);

        $products = array();

        while($stmt->fetch()) {
            $product = array();
            $product['productId'] = $productid;
            $product['productName'] = $productname;
            $product['productDescription'] = $productdesc;
            $product['productQuantity'] = $productquantity;
            $product['productPrice'] = $productprice;
            $product['productImage'] = $productimage;

            array_push($products, $product);
        }
        return $products;
    }
}