<?php

class DB_Functions {

    private $db;
    private $con;
    
    //put your code here
    // constructor
    function __construct() {
        include_once 'db_connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->con = $this->db->connect();
    }
    
    // destructor
    function __destruct() {

    }
    
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($id_site, $first_name, $last_name, $email, $password, $notification) {
        // insert user into database
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $result = $this->con->query("INSERT INTO sp_mobile_customer(id_site, first_name, last_name, email, password, salt, date_add, date_upd, last_login, notifications_enabled) VALUES('$id_site', '$first_name', '$last_name', '$email', '$encrypted_password', '$salt', NOW(), NOW(), NOW(), '$notification')");
        // check for successful store
        if ($result) {
            // get user details
            $id = $this->con->insert_id; // last inserted id
            $result = $this->con->query("SELECT * FROM sp_mobile_customer WHERE id_customer = '$id'") or die(mysql_error());
            // return user details
            if ($result->num_rows > 0) {
                return mysqli_fetch_array($result);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Checking if email exists in DB or not
     * @param email
     * returns user id
     */
    public function getUserByEmail($email) {
        $result = $this->con->query("SELECT id_customer FROM sp_mobile_customer WHERE email='$email'");
        if ($result) {
            return mysqli_fetch_array($result);
        } else {
            return false;
        }
    }

    /**
     * Fetching user details
     * @param email, password
     * returns user details
     */
    public function getUserByEmailAndPassword($email, $password) {
        $result = $this->con->query("SELECT password, salt FROM sp_mobile_customer WHERE email='$email'");
        if ($result) {
            $obj = mysqli_fetch_array($result);
            $salt= $obj["salt"];
            $encrypted = hash('sha512', $password.$salt);
            if (strcmp($encrypted,  $obj["password"]) == 0) {
                $this->con->query("UPDATE sp_mobile_customer SET last_login=NOW() WHERE email='$email'");
                $result = $this->con->query("SELECT * FROM sp_mobile_customer WHERE email='$email'");
                return mysqli_fetch_array($result);
            }
            else {
                return false;
            }
            
        } else {
            return false;
        }
    }
    
	/**
     * Update GCM registration ID
     * @param email, gcm registration ID
     * 
    **/
    public function updateGcmID($email, $gcm_registration_id) {
        $response = array();
        $result = $this->con->query("UPDATE sp_mobile_customer SET gcm_registration_id = '$gcm_registration_id' WHERE email = '$email'");
        
        if ($result) {
            // User successfully updated
            $response["error"] = false;
            $response["message"] = 'GCM registration ID updated successfully';
        } else {
            // Failed to update user
            $response["error"] = true;
            $response["message"] = "Failed to update GCM registration ID";
        } 
        return $response;
    }


    /**
     * Updating user pofile
     * @param email, variable to update, new value
     * result statut of update
    **/
    public function updateUserByEmail($email, $variable, $new_value) {
        $result = $this->con->query("UPDATE sp_mobile_customer SET ".$variable." = '$new_value' WHERE email = '$email'");
        if ($result) {
            // User successfully updated
            return $result;
        } else {
            // Failed to update user
            return false;
        } 
    }


    /**
     * Updating user pofile
     * @param email, variable to update, new value
     * result statut of update
    **/
    public function updateUserPassword($email, $password) {

        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $result = $this->con->query("UPDATE sp_mobile_customer SET password = '$encrypted_password', salt = '$salt' WHERE email = '$email'");
        if ($result) {
            // User successfully updated
            return $result;
        } else {
            // Failed to update user
            return false;
        } 
    }

    /**
     * Encrypting password
     * @param password
     * returns salt, encrypted_password
     */
    public function hashSSHA($password) {
        $nbr = 5;
        $salt_byte = openssl_random_pseudo_bytes($nbr, $cstrong);
        $salt = bin2hex($salt_byte);
        $encrypted = hash('sha512', $password.$salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = hash('sha512', $password.$salt);
        return $hash;
    }
}

?>