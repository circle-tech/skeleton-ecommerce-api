<?php

class APIOperation {
    
    private $con;

    function __construct() {
        require_once 'Connect.php';

        $db = new Connect();
        $this->con = $db->connect();
    }

    function registerAcc($ic, $fullname, $email, $password) {
        $stmt = $this->con->prepare("INSERT INTO account (ic, fullname, email, pass) VALUES (?, ?, ?, ?)");
        $password = md5($password);
        $stmt->bind_param("isss", $ic, $fullname, $email, $password);
        if($stmt->execute())
            return true;
        return false; 
    }

    function loginAcc($ic, $password) {
        $stmt = $this->con->prepare("SELECT * FROM account WHERE ic=? AND pass=?");
        $password = md5($password);
        $stmt->bind_param("is", $ic, $password);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1)
            return true;
        return false;
    }

    function getAcc($ic) {
        $stmt = $this->con->prepare("SELECT ic, fullname, email, type FROM account WHERE ic=?");
        $stmt->bind_param("i", $ic);
        $stmt->execute();
        $stmt->bind_result($ic, $fullname, $email, $type);

        $accounts = array(); 

        while($stmt->fetch()) {
            $acc  = array();
            $acc['ic'] = $ic; 
            $acc['fullname'] = $fullname; 
            $acc['email'] = $email; 
            $acc['type'] = $type; 

            array_push($accounts, $acc);
        }
        return $accounts; 
    }
}