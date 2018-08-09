<?php
$salt = "#~9=c}qK.,FP8ca67,~1XO[|FM+;9(2o79i5BO&oU?+_V<Nij>(U8tEpg&RIjmj8";

// Change to true to run
if (false)
{

	include("../dbconnect.php");

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	$sql = "SELECT id, fname, lname FROM owners ";
	$result = mysqli_query($conn, $sql);

	$usernameArray = array();


	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {

			$count = 0;

			do {
				$newUsername = strtolower($row['lname'] . $row['fname'][$count]);

				if (!in_array($newUsername, $usernameArray)){
					array_push($animals, $data);
					break;
				}

				$count++;

			} while (true);

			$newPassword = crypt(strrev(strtolower($newUsername)), $salt);


			$updateUsers = "UPDATE owners SET username='" . $newUsername . "', password='" . $newPassword ."' WHERE id=" . $row['id'];

			if ($conn->query($updateUsers) === TRUE) {
				echo "Updating User ID " . $row['id'] . " with username " . $newUsername . " and password " . strrev(strtolower($newUsername)) . "<br>";
			} else {
				echo "Error updating record: " . $conn->error . "<br>";
			}

		}


	}
}else{
	// You can use this to manually create passwords
	//echo crypt("thepassword", $salt);
	echo "Not Running!";
}



?>