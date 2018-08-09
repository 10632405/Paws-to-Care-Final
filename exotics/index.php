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

$sql = "SELECT exotics.id, exotics.name, exotics.species, exotics.sex, exotics.neutered, exotics.birthdate, owners.fname, owners.lname, owners.add1 , owners.add2, owners.city, owners.st, owners.zip FROM exotics 
INNER JOIN exoticsowners on exotics.id = exoticsowners.exoticsFk
INNER JOIN owners on exoticsowners.ownersFk = owners.id";
$result = mysqli_query($conn, $sql);




$toJSON = array();

$animals = array();

if (mysqli_num_rows($result) > 0) {
    // Change to new array with readable keys
    while($row = mysqli_fetch_assoc($result)) {
        
        $data = array();
        $data['id'] = $row["id"];
        $data['Name'] = $row["name"];
        $data['Species'] = $row["species"];
        $data['Sex'] = $row["sex"];
        $data['Neutered'] = $row["neutered"];
        
		$birthdate = new DateTime($row["birthdate"]);
		$today = new DateTime(date());
		$age = $today->diff($birthdate);
        
        $data['Age - Birthdate'] = $age->y . " - " . date_format($birthdate,"F d, Y");
        
        $data['Owners'] = $row["fname"] . " " . $row["lname"];
        $data['ownerDetail'] = $row["fname"] . " " . $row["lname"] . "<p>" . $row["add1"] . " " . $row["add2"] . "<br>" . $row["city"] . " " . $row["st"] . ", ". $row["zip"] ."</p>";
		$data['Notes'] = " ";

		$notes = " ";

		$sqlNotes = "SELECT vetName, date, note FROM exoticnotes WHERE exoticsFk = " . $row["id"];

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

$tableHeadings["Name"] = "The pets's name";
$tableHeadings["Species"] = "The pets's breed";
$tableHeadings["Sex"] = "The pets's gender";
$tableHeadings["Neutered"] = " ";
$tableHeadings["Age"] = "How old is the pet?";
$tableHeadings["Owners"] = "Who are the lucky owner's?";
$tableHeadings["Notes"] = "Any additional information about the pet";

$toJSON["tableHeadings"] = $tableHeadings;

echo json_encode($toJSON);

mysqli_close($conn);


?>