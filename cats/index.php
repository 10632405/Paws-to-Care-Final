<?php
session_start(); 
	
if(!isset($_SESSION['isAdmin'])){
		header("location:../index.php");
}
include("../dbconnect.php");

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT cats.id, cats.name, cats.breed, cats.sex, cats.shots, cats.declawed, cats.neutered, cats.birthdate, owners.fname, owners.lname, owners.add1 , owners.add2, owners.city, owners.st, owners.zip FROM cats
INNER JOIN catsowners on cats.id = catsowners.catsFk
INNER JOIN owners on catsowners.ownersFk = owners.id";
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
        $data['Declawed'] = $row["licensed"];
        $data['Neutered'] = $row["neutered"];
        
        $birthdate = new DateTime($row["birthdate"]);
		$today = new DateTime(date());
		$age = $today->diff($birthdate);
        
        $data['Age - Birthdate'] = $age->y . " - " . date_format($birthdate,"F d, Y");
        $data['Owners'] = $row["fname"] . " " . $row["lname"];
		$data['ownerDetail'] = $row["fname"] . " " . $row["lname"] . "<p>" . $row["add1"] . " " . $row["add2"] . "<br>" . $row["city"] . " " . $row["st"] . ", ". $row["zip"] ."</p>";
		
		$data['Notes'] = " ";

		$notes = " ";

		$sqlNotes = "SELECT vetName, date, note FROM catnotes WHERE catsFk = " . $row["id"];

		$noteResults = mysqli_query($conn, $sqlNotes);
		if (mysqli_num_rows($noteResults) > 0) {
			$count = 1;
			while($note = mysqli_fetch_assoc($noteResults)) {
				$notes .= "<div class='card m-2'>";
				$notes .= "<div class='card-body'>";
				$notes .= "<h5 class='card-title'> Note " . $count . "</h5>";
				$notes .= "<h6 class='card-subtitle mb-2 text-muted'>". $note['vetName'] . " | " . date_format(new DateTime($note["date"]),"F d, Y") . "</h6>";
				$notes .= "<p class='card-text'>" . $note['note'] . "</p>";
				$notes .= "</div>";
				$notes .= "</div>";
				$count++;
			}
		}else{
			$notes = "There are no notes";
		}

		$data['noteDetail'] = base64_encode($notes);
        
        
        array_push($animals, $data);
        
    }
} else {
    echo "0 results";
}

$toJSON["animals"] = $animals;

// TODO move this into MySQL DB
$tableHeadings = array();

$tableHeadings["Name"] = "The cat's name";
$tableHeadings["Breed"] = "The cat's breed";
$tableHeadings["Sex"] = "The cat's gender";
$tableHeadings["Shots"] = "Are the cat's shots up to date?";
$tableHeadings["Age"] = "How old is the cat?";
$tableHeadings["Declawed"] = " ";
$tableHeadings["Neutered"] = " ";
$tableHeadings["Owners"] = "Who are the lucky owner's?";
$tableHeadings["Notes"] = "Any additional information about the cat";

$toJSON["tableHeadings"] = $tableHeadings;

echo json_encode($toJSON);

mysqli_close($conn);


?>