<?php 

define('host', 'localhost');
define('user', 'root');
define('pass', '');
define('database', '');

class Connect {
    private $con;

    function __construct() { }
    
    function connect() {
        $this->con = new mysqli(host, user, pass, database);

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        return $this->con;
    }
}