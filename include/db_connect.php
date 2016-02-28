<?php

class DB_Connect {
  
    // constructor
    function __construct() {
  
    }
  
    // destructor
    function __destruct() {
        // $this->close();
    }
  
    // Connecting to database
    public function connect() {
        require_once 'config.php';

        // connecting to mysql
        //$connexion  = new PDO('mysql:host=http://app.spectro-commerce.com/;dbname=miraldev;charset=utf8', 'miraldev', 'w4x5W4Vk');
        $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        //echo 'Status : '.$connexion->getAttribute(PDO::ATTR_CONNECTION_STATUS).PHP_EOL;
  
        // return database handler
        return $con;
    }
  
    // Closing database connection
    public function close() {
        mysql_close($con);
    }
  
} 

?>