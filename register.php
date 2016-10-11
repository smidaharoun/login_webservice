<?php

require_once './include/db_functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['password'])) {

    // receiving the post params
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // check if user is already existed with the same email
    if ($db->getUserByEmail($email)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with " . $email;
        echo json_encode($response);
    } else {
        // create a new user
        $user = $db->storeUser($first_name, $last_name, $email, $password, $notification);
        $response["error_msg2"] = "No user existed with " . $email;
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["user"]["first_name"] = $user["first_name"];
            $response["user"]["last_name"] = $user["last_name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["date_add"] = $user["date_add"];
            $response["user"]["date_upd"] = $user["date_upd"];
            $response["user"]["last_login"] = $user["last_login"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (first_name, last_name, email, password) is missing!";
    echo json_encode($response);
}
?>
