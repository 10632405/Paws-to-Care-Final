<?php
session_start(); 
	
if(!isset($_SESSION['loggedIn'])){
		header("location:login.php");
}

if(isset($_SESSION['isAdmin'])){
		header("location:admin/animals.php");
}
	
$currentPage = __FILE__;

include("dbconnect.php");

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sqlDogs = "SELECT dogs.id, dogs.name, dogs.breed, dogs.sex, dogs.shots, dogs.licensed, dogs.neutered, dogs.birthdate, dogs.weight FROM dogsOwners INNER JOIN dogs on dogsowners.dogsFk = dogs.id  WHERE dogsowners.ownersFk = " . $_SESSION['userID'];
$dogResult = mysqli_query($conn, $sqlDogs);
		
$sqlCat = "SELECT cats.id, cats.name, cats.breed, cats.sex, cats.shots, cats.declawed, cats.neutered, cats.birthdate FROM catsOwners INNER JOIN cats on catsowners.catsFk = cats.id  WHERE catsowners.ownersFk = " . $_SESSION['userID'];
$catResult = mysqli_query($conn, $sqlCat);
		
$sqlExotic = "SELECT exotics.id, exotics.name, exotics.species, exotics.sex, exotics.neutered, exotics.birthdate FROM exoticsOwners INNER JOIN exotics on exoticsowners.exoticsFk = exotics.id  WHERE exoticsowners.ownersFk = " . $_SESSION['userID'];
$exoticsResult = mysqli_query($conn, $sqlExotic);

$dogData = $dogResult->fetch_all(MYSQLI_ASSOC);
$catData = $catResult->fetch_all(MYSQLI_ASSOC);
$exoticData = $exoticsResult->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Paws to Care Admin - Pets</title>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon" sizes="16x16">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/pets.css" type="text/css">
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
	
	<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>
</head>

<body>
 <?php include("nav.php");?>

  <div id="homeBanner" class="jumbotron text-center mt-2 container">
    <h1>Paws to Care</h1>

</div>

<main class="container-fluid m-1">
<div id="mainTableDiv" class="m-2">
		
		<?php 
			if (sizeof($dogData) > 0){
				echo "<div class='animalDiv'";
				echo "<center><h2>Dogs</h2></center>";
				echo "    <table class='animalTable' id='dogTable'>
							<tbody>
							    <tr>
									<th title=\"The dog's name\" data-toggle='tooltip' data-placement='bottom' id='name'>Name</th>
									<th title=\"The dog's breed\" data-toggle='tooltip' data-placement='bottom 'id='breed'>Breed</th>
									<th title=\"The dog's gender\" data-toggle='tooltip' data-placement='bottom' id='sex'>Sex</th>
									<th title=\"Are the dog's shots up to date?\" data-toggle='tooltip' data-placement='bottom' id='shots'>Shots</th>
									<th title='Has the dog been licensed in the city it lives?' data-toggle='tooltip' data-placement='bottom' id='licensed'>Licensed</th>
									<th title='' data-toggle='tooltip' data-placement='bottom' id='neutered'>Neutered</th>
									<th title='' data-toggle='tooltip' data-placement='bottom' id='age - birthdate'>Age - Birthdate</th>
									<th title='S &lt; 20, M &lt;50, L &lt;100 (Lbs)' data-toggle='tooltip' data-placement='bottom' id='weight'>Weight</th>
								</tr>";
					foreach ($dogData as $row){
						echo "<tr>";
						echo "<td>" . $row['name'] . "</td>";
						echo "<td>" . $row['breed'] . "</td>";
						echo "<td>" . $row['sex'] . "</td>";
						
						if ($row['shots'] == "1" ){
							echo "<td><span class='fa fa-check' style='font-size: 18pt; color: #00be37;'></span></td>";
						}else{
							echo "<td><span class='fa fa-close' style='font-size: 18pt;'></span></td>";
						}
						if ($row['licensed'] == "1" ){
							echo "<td><span class='fa fa-check' style='font-size: 18pt; color: #00be37;'></span></td>";
						}else{
							echo "<td><span class='fa fa-close' style='font-size: 18pt;'></span></td>";
						}
						if ($row['neutered'] == "1"){
							echo "<td><span class='fa fa-check' style='font-size: 18pt; color: #00be37;'></span></td>";
						}else{
							echo "<td><span class='fa fa-close' style='font-size: 18pt;'></span></td>";
						}
						
						$birthdate = new DateTime($row["birthdate"]);
						$today = new DateTime(date());
						$age = $today->diff($birthdate);
						echo "<td>" . $age->y . " - " . date_format($birthdate,"F d, Y") . "</td>";
						
						if (intval($row['weight']) >= 100) {
							echo "<td>L - " . $row['weight'] . "Lbs</td>";
						} elseif (intval($row['weight']) > 20 && intval($row['weight']) <= 100) {
							echo "<td>M - " . $row['weight'] . "Lbs</td>";
						} else {
							echo "<td>S - " . $row['weight'] . "Lbs</td>";
						}
			
						echo "</tr>";
						
					}
					
				echo "</tbody>
						</table>";
				echo "</div>";
			}
			if (sizeof($catData) > 0){
				echo "<div class='animalDiv'";
				echo "<center><h2>Cats</h2></center>";
				echo "    <table class='animalTable' id='catTable'>
							<tbody>
							    <tr>
									<th title=\"The cat's name\" data-toggle='tooltip' data-placement='bottom' id='name'>Name</th>
									<th title=\"The cat's breed\" data-toggle='tooltip' data-placement='bottom' id='breed'>Breed</th>
									<th title=\"The cat's gender\" data-toggle='tooltip' data-placement='bottom' id='sex'>Sex</th>
									<th title=\"Are the cat's shots up to date?\" data-toggle='tooltip' data-placement='bottom' id='shots'>Shots</th>
									<th title='' data-toggle='tooltip' data-placement='bottom' id='declawed'>Declawed</th>
									<th title='' data-toggle='tooltip' data-placement='bottom' id='neutered'>Neutered</th>
									<th title='' data-toggle='tooltip' data-placement='bottom' id='age - birthdate'>Age - Birthdate</th>
								</tr>";
					foreach ($catData as $row){
						echo "<tr>";
						echo "<td>" . $row['name'] . "</td>";
						echo "<td>" . $row['breed'] . "</td>";
						echo "<td>" . $row['sex'] . "</td>";
						if ($row['shots'] == "1" ){
							echo "<td><span class='fa fa-check' style='font-size: 18pt; color: #00be37;'></span></td>";
						}else{
							echo "<td><span class='fa fa-close' style='font-size: 18pt;'></span></td>";
						}
						if ($row['declawed'] == "1" ){
							echo "<td><span class='fa fa-check' style='font-size: 18pt; color: #00be37;'></span></td>";
						}else{
							echo "<td><span class='fa fa-close' style='font-size: 18pt;'></span></td>";
						}
						if ($row['neutered'] == "1"){
							echo "<td><span class='fa fa-check' style='font-size: 18pt; color: #00be37;'></span></td>";
						}else{
							echo "<td><span class='fa fa-close' style='font-size: 18pt;'></span></td>";
						}
						
						$birthdate = new DateTime($row["birthdate"]);
						$today = new DateTime(date());
						$age = $today->diff($birthdate);
						echo "<td>" . $age->y . " - " . date_format($birthdate,"F d, Y") . "</td>";
						echo "</tr>";
						
					}
					
				echo "</tbody>
						</table>";
				echo "</div>";
			}
			if (sizeof($exoticData) > 0){
				echo "<div class='animalDiv'";
				echo "<center><h2>Exotics</h2></center>	";
				echo "   <table class='animalTable' id='catTable'>
							<tbody>
								<tr>
									<th title=\"The pets's name\" data-toggle='tooltip' data-placement='bottom' id='name'>Name</th>
									<th title=\"The pets's breed\" data-toggle='tooltip' data-placement='bottom' id='species'>Species</th>
									<th title=\"The pets's gender\" data-toggle='tooltip' data-placement='bottom' id='sex'>Sex</th>
									<th title='' data-toggle='tooltip' data-placement='bottom' id='neutered'>Neutered</th>
									<th title='' data-toggle='tooltip' data-placement='bottom' id='age - birthdate'>Age - Birthdate</th>
								</tr>";
					foreach ($exoticData as $row){
						echo "<tr>";
						echo "<td>" . $row['name'] . "</td>";
						echo "<td>" . $row['species'] . "</td>";
						echo "<td>" . $row['sex'] . "</td>";
						if ($row['neutered'] == "1"){
							echo "<td><span class='fa fa-check' style='font-size: 18pt; color: #00be37;'></span></td>";
						}else{
							echo "<td><span class='fa fa-close' style='font-size: 18pt;'></span></td>";
						}
						
						$birthdate = new DateTime($row["birthdate"]);
						$today = new DateTime(date());
						$age = $today->diff($birthdate);			
						echo "<td>" . $age->y . " - " . date_format($birthdate,"F d, Y") . "</td>";
			
						echo "</tr>";
						
					}
					
				echo "</tbody>
						</table>";
				echo "</div>";
			}

	  
	    
	  ?>

</div>

</main>

<?php include("footer.php");?>

</body>
</html>
