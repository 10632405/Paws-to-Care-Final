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

$isAdmin = false;


function hardCodeUserValidation($user, $pass){
	// TODO Move username and passwords to DB
	// $sql = "SELECT id, isAdmin FROM ??? WHERE username='$username' and password='$encrypted_password'"

	$users = array();

	// This could be stored in a seperate table or we could move to owners db and make it more of a users db
	// Admin User
	$data = array();
	$data['id'] = 1001;
	$data['username'] = "admin";
	$data['password'] = "admin";
	$data['isAdmin'] = true;
	array_push($users, $data);

	// These will be stored in the owners DB we will need to create a field for username and a system for it. Maybe email address?
	// Test User 1
	$data = array();
	$data['id'] = 1;
	$data['username'] = "emeryr";
	$data['password'] = "pass";
	$data['isAdmin'] = false;
	array_push($users, $data);

	// Test User 2
	$data = array();
	$data['id'] = 2;
	$data['username'] = "mosesl";
	$data['password'] = "pass";
	$data['isAdmin'] = false;
	array_push($users, $data);

	foreach ($users as $value) {
		foreach($value as $u){

			// Username is here lets verify password
			if (strcmp($u,$user) == 0 && $u != null){
				if (strcmp($value["password"], $pass) == 0){
					$GLOBALS['isAdmin'] = $value["isAdmin"];
					return true;
				}
			}
		}
	}


	return false;
}

function validateUser($user, $pass) {
	return hardCodeUserValidation($user, $pass);
}

if(validateUser($username, $password)){

	$_SESSION['loggedIn'] = true;

	if ($isAdmin){
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