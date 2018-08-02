<?php
include("../dbconnect.php");

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT dogs.id, dogs.name, dogs.breed, dogs.sex, dogs.shots, dogs.licensed, dogs.neutered, dogs.birthdate, dogs.weight, owners.fname, owners.lname FROM dogs
INNER JOIN dogsowners on dogs.id = dogsowners.dogsFk
INNER JOIN owners on dogsowners.ownersFk = owners.id";


$result = mysqli_query($conn, $sql);

$toJSON = array();

$animals = array();

if (mysqli_num_rows($result) > 0) {
    // Change to new array with readable keys
    while($row = mysqli_fetch_assoc($result)) {
        
        $data = array();
        $data['id'] = $row["id"];
        $data['Name'] = $row["name"];
        $data['Breed'] = $row["breed"];
        $data['Sex'] = $row["sex"];
        $data['Shots'] = $row["shots"];
        $data['Licensed'] = $row["licensed"];
        $data['Neutered'] = $row["neutered"];
        
		$birthdate = new DateTime($row["birthdate"]);
		$today = new DateTime(date());
		$age = $today->diff($birthdate);
        
        $data['Age - Birthdate'] = $age->y . " - " . date_format($birthdate,"M/d/Y");
        
        $data['Weight'] = $row["weight"];
		$data['Owners'] = $row["fname"] . " " . $row["lname"];
		$data['Notes'] = " ";
        
        
        array_push($animals, $data);
        
    }
} else {
    echo "0 results";
}

$toJSON["animals"] = $animals;

// TODO move this into MySQL DB
$tableHeadings = array();

$tableHeadings["Name"] = "The dog's name";
$tableHeadings["Breed"] = "The dog's breed";
$tableHeadings["Sex"] = "The dog's gender";
$tableHeadings["Shots"] = "Are the dog's shots up to date?";
$tableHeadings["Age"] = "How old is the dog?";
$tableHeadings["Weight"] ="S < 20, M <50, L <100 (Lbs)";
$tableHeadings["Licensed"] = "Has the dog been licensed in the city it lives?";
$tableHeadings["Neutered"] = " ";
$tableHeadings["Owners"] = "Who are the lucky owner's?";
$tableHeadings["Notes"] = "Any additional information about the dog";

$toJSON["tableHeadings"] = $tableHeadings;

echo json_encode($toJSON);

mysqli_close($conn);


?>