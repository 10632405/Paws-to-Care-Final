<?php
	session_start(); 
	
	if(!isset($_SESSION['isAdmin'])){
		header("location:../pets.php");
}
	
$currentPage = __FILE__;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Paws to Care Admin - Animals</title>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon" sizes="16x16">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
	

	
</head>

<body>
 <?php include("../nav.php");?>

  <div id="homeBanner" class="jumbotron text-center mt-2 container">
    <h1>Paws to Care</h1>

</div>

<main class="container-fluid m-1">
<div id="mainTableDiv" class="m-2">
	<div id="mainTableDivOuter">
  <div id="mainTableDivCenter"><h1>Click on which type of animal you want to look at from the menu on the top right of the screen.</h1></div>
	</div>
</div>

</main>

<?php include("../footer.php");?>

<script src="js/animals.js" type="text/javascript"></script>

</body>
</html>
