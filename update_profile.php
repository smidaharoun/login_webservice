<?php

require_once './include/db_functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['update']) && isset($_POST['email_user']) && isset($_POST['new_value'])) {
	
	$update = $_POST['update'];
	$response["update"] = $update;

	$email = $_POST['email_user'];
	$new_value = $_POST['new_value'];
	$response["email_user"] = $email;
	$result = $db->getUserByEmail($email);
	if ($result) {

		// Update email, first_name, last_name, notification
		if ($update == 'email' || $update == 'first_name' || $update == 'last_name' || $update == 'notifications_enabled') {

			// Update User profile
			$result = $db->updateUserByEmail($email, $update, $new_value);
			if ($result) {
            // User successfully updated
				$response["error"] = false;
				$response["message"] = $update." updated successfully";
				$response["variable"] = $update;
				$response["new_value"] = $new_value;
			} else {
            // Failed to update user
				$response["error"] = true;
				$response["message"] = "Failed to update ".$update;
			}
		}

		// Update password 
		elseif ($update == 'password') {
			if (isset($_POST['old_value'])) {
				$old_value = $_POST['old_value'];
				$result = $db->getUserByEmailAndPassword($email, $old_value);
				if ($result) {
					
					// Update User profile
					$result = $db->updateUserPassword($email, $new_value);
					if ($result) {
            			// User successfully updated
						$response["error"] = false;
						$response["message"] = $update." updated successfully";
						$response["variable"] = $update;
						$response["new_value"] = $new_value;
					} else {
            			// Failed to update user
						$response["error"] = true;
						$response["message"] = "Failed to update ".$update;
					}
				}

				else {
					// Wrong old password
					$response["error"] = true;
					$response["message"] = "Failed to update ".$update.", wrong old value!";
				}
			}

			else {
				// required post params are missing
				$response["error"] = TRUE;
				$response["error_msg"] = "Required parameters update, email, new_value, old_value are missing!";

			}
		}

			// Invalide input
		else {
			$response["error"] = TRUE;
			$response["error_msg"] = "Invalid input for parameter update";
		}

	}

	else {
		$response["error"] = TRUE;
		$response["error_msg"] = "Wrong email, Please try again!";
	}
} 


else {
	// required post params are missing
	$response["error"] = TRUE;
	$response["error_msg"] = "Required parameters update, email, new_value are missing!";

}

echo json_encode($response);

?>