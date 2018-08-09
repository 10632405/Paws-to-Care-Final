<?php
session_start();

include("dbconnect.php");

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

$salt = "#~9=c}qK.,FP8ca67,~1XO[|FM+;9(2o79i5BO&oU?+_V<Nij>(U8tEpg&RIjmj8";

$password = crypt($password, $salt);

$isAdmin = false;

$query = "SELECT id, isadmin, fname FROM owners WHERE username='" . $username . "' AND password='" . $password . "'";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (sizeof($row) > 1){

	$_SESSION['loggedIn'] = true;
	$_SESSION['userID'] = $row['id'];
	$_SESSION['fname'] = $row['fname'];

	if ($row['isadmin'] == "1"){
		$_SESSION['isAdmin'] = true;
		header("location:admin/animals.php");
	}else{
		
		header("location:pets.php");
	}
}else{
	header("location:login.php?error=1");
}


mysqli_close($conn);

?>