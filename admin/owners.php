<?php
	session_start(); 
	
	if(!isset($_SESSION['isAdmin'])){
		header("location:../pets.php");
}
	
$currentPage = __FILE__;

include("../dbconnect.php");

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



$howManyPerPage = 10;
$maxPaginationButtons = 5;

$pageGETURL = "";

if(isset($_GET['pagenum']) && is_numeric($_GET['pagenum'])){
	$pageNum = $_GET['pagenum'] - 1;
	$pageGETURL = "&pagenum=". $_GET['pagenum'];
}else{
	$pageNum = 0;
}

$sortSQL = "";

if (isset($_GET['sortby']) && isset($_GET['sort']))
{
	//Clean any input in
	$sortBy = mysqli_real_escape_string($conn, $_GET['sortby']);
	$sort = mysqli_real_escape_string($conn, $_GET['sort']);

	$validInput = false;

	if($sortBy == "address" || $sortBy == "name")
	{
		if($sort == "asc" || $sort == "desc")
		{
			$validInput = true;
		}
}

$sortGETURL = "";
	
	
if($validInput){
	if ($sortBy == "address"){
		$sortBy = "add1";
	}
	if ($sortBy == "name"){
		$sortBy = "fname " . $sort . ",lname ";
	}
		$sortSQL = "ORDER BY ". $sortBy . " " . $sort;
	}
	$sortGETURL = "&sortby=" . $_GET['sortby'] . "&sort=" . $_GET['sort'];
}

$filterBySQL = "";
$filterByGETURL = "";

if (isset($_GET['filterby']) && isset($_GET['filterkey']))
{
	$filterBy = mysqli_real_escape_string($conn, $_GET['filterby']);
	$filterKey = mysqli_real_escape_string($conn, $_GET['filterkey']);
	
	if ($filterBy == "filterName"){
		$filterBy = "fname";
	}
	if ($filterBy == "filterAddress"){
		$filterBy = "add1";
	}
	
	$filterBySQL = " WHERE " . $filterBy . " LIKE '" . $filterKey . "%'";
	$filterByGETURL = "&filterby=" . $_GET['filterby'] . "&filterkey=" . $filterKey;
	
}

$startPage = $pageNum * $howManyPerPage;


$sql = "SELECT id, fname, lname, add1 , add2, city, st, zip FROM owners ". $filterBySQL . $sortSQL . " LIMIT ". $startPage . ", " . $howManyPerPage;
$result = mysqli_query($conn, $sql);

$sqlCount = "SELECT COUNT(id) AS count FROM owners " . $filterBySQL;
$sqlResult = mysqli_query($conn, $sqlCount);

$sqlRow = mysqli_fetch_assoc($sqlResult);

$howManyPages = round($sqlRow['count'] / $howManyPerPage);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Paws to Care Admin - Owners</title>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon" sizes="16x16">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/owners.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
</head>

<body>
 <?php include("../nav.php"); //echo $sql; echo "<br>" . $howManyPages;?>

  <div id="homeBanner" class="jumbotron text-center mt-2 container">
    <h1>Paws to Care</h1>

</div>

<main class="container-fluid m-1">

	<div class="table m-2">
		<div class="tableRow">
			<?php 
				if ($_GET['filterby'] == "filterName"){
					echo "<div class='tableCell'><span><input class='filterField' placeholder='Search by Name' onkeyup='filterField(this)' id='filterName' value='" . $filterKey . "'></span></div>";
				}else{
					echo "<div class='tableCell'><span><input class='filterField' placeholder='Search by Name' onkeyup='filterField(this)' id='filterName'></span></div>";
				}
				
				if ($_GET['filterby'] == "filterAddress"){
					echo "<div class='tableCell'><span><input class='filterField' placeholder='Search by Address' onkeyup='filterField(this)' id='filterAddress' value='" . $filterKey . "'></span></div>";
				}else{
					echo "<div class='tableCell'><span><input class='filterField' placeholder='Search by Address' onkeyup='filterField(this)' id='filterAddress'></span></div>";
				}
		  
		  
			?>
			<div class="tableCell"><span></span></div>
				</div>
		<div class="tableRow">
			<?php
				// This is really ugly and I don't like it. It needs to be refactored
				if (isset($_GET['sortby']) && isset($_GET['sort']))
				{
					if($_GET['sortby'] == "name")
					{
						if ($_GET['sort'] == "asc"){
							echo "<div class='tableCell header' onclick='window.location.href=\"owners.php?sortby=name&sort=desc" . $pageGETURL . $filterByGETURL . "\";'><span><strong>Name▲</strong></span></div>";
						}elseif ($_GET['sort'] == "desc"){
							echo "<div class='tableCell header' onclick='window.location.href=\"owners.php?sortby=name&sort=asc" . $pageGETURL . $filterByGETURL . "\";'><span><strong>Name▼</strong></span></div>";
						}else{
							echo "<div class='tableCell header'><span><strong>Name</strong></span></div>";
						}
							echo "<div class='tableCell header' onclick='window.location.href=\"owners.php?sortby=address&sort=asc" . $pageGETURL . $filterByGETURL . "\";'><span><strong>Address</strong></span></div>";
					}elseif($_GET['sortby'] == "address"){
						echo "<div class='tableCell header' onclick='window.location.href=\"owners.php?sortby=name&sort=asc" . $pageGETURL . $filterByGETURL . "\";'><span><strong>Name</strong></span></div>";
						if ($_GET['sort'] == "asc"){
							echo "<div class='tableCell header' onclick='window.location.href=\"owners.php?sortby=address&sort=desc" . $pageGETURL . $filterByGETURL . "\";'><span><strong>Address▲</strong></span></div>";
						}elseif ($_GET['sort'] == "desc"){
							echo "<div class='tableCell header' onclick='window.location.href=\"owners.php?sortby=address&sort=asc" . $pageGETURL . $filterByGETURL . "\";'><span><strong>Address▼</strong></span></div>";
						}else{
							echo "<div class='tableCell header'><span><strong>Address</strong></span></div>";
						}
					}else{
						echo "<div class='tableCell header' onclick='window.location.href=\"owners.php?sortby=name&sort=asc" . $pageGETURL . $filterByGETURL . "\";'><span><strong>Name</strong></span></div>";
						echo "<div class='tableCell header'><span><strong>Address</strong></span></div>";
					}
				}else{
					echo "<div class='tableCell header' onclick='window.location.href = \"owners.php?sortby=name&sort=asc" . $pageGETURL . $filterByGETURL . "\";'><span><strong>Name</strong></span></div>";
					echo "<div class='tableCell header' onclick='window.location.href = \"owners.php?sortby=address&sort=asc" . $pageGETURL . $filterByGETURL . "\";'><span><strong>Address</strong></span></div>";
				}

				echo "<div class='tableCell header'><span><strong>Pets</strong></span></div>";
		  
		  ?>
		</div>
<?php
	

	
if (mysqli_num_rows($result) > 0) {
	

	$data   = $result->fetch_all(MYSQLI_ASSOC);
	
foreach ($data as $row) {

	echo "<div class='tableRow'>";
	echo "<div class='tableCell'><span>" . $row['fname'] . " ". $row['lname'] . "</span></div>";
	echo "<div class='tableCell'><span>" . $row["add1"] . " " . $row["add2"] . "<br>" . $row["city"] . " " . $row["st"] . ", ". $row["zip"] . "</span></div>";
	echo "<div class='tableCell'><span>" . "<button type='button' class='btn btn-outline-secondary btn-sm' data-toggle='modal' data-target='#petsModal" . $row["id"] . "' style='display: block;margin: auto;'>Pets</button></span></div>";
	echo"</div>";
	    
	    
    }
}
	
?>
	</div>
    <div id="paginationButtons">
        <nav aria-label="pagination">
            <ul id="paginationItems" class="pagination pagination-sm justify-content-end" style="margin:15px 0">
                
                
                <?php
	                
	                if ($pageNum == 0){
		                echo "<li class='page-item disabled'><a class='page-link' href='#' tabindex='-1'>Previous</a></li>";
	                }else{
		                echo "<li class='page-item'><a class='page-link'href='owners.php?pagenum=" . $pageNum . $sortGETURL . $filterByGETURL . "' tabindex='-1'>Previous</a></li>";
	                }
	                
	                $numOfPageButtons = 0;
	               
	                if ($howManyPages > $maxPaginationButtons){
						$numOfPageButtons = $maxPaginationButtons;
	                }else{
						$numOfPageButtons = $howManyPages;
	                }
	                
	                $startingPoint = 0;
	                $endingPoint = $numOfPageButtons;
	                
	                if (($pageNum + 1) > $maxPaginationButtons)
	                {
		                $startingPoint = ($pageNum + 1) - $numOfPageButtons;
		                $endingPoint = $pageNum + 1;
	                }
	           
	                for ($i = $startingPoint; $i < $endingPoint; $i++)
		                {
			                if ($i == $pageNum){
				                echo "<li class='page-item active'><a class='page-link' href='owners.php?pagenum=". ($i + 1) . $sortGETURL . $filterByGETURL . "' >" . ($i + 1) . "</a></li>";
			                }else{
				                echo "<li class='page-item'><a class='page-link' href='owners.php?pagenum=". ($i + 1) . $sortGETURL . $filterByGETURL . "' >" . ($i + 1) . "</a></li>";
			                }
			                
		                }
		                
		            if (($pageNum + 1) == $howManyPages){
			            echo "<li class='page-item disabled'><a class='page-link' href='#' >" . "Next</a></li>";
		            }else{
			            echo "<li class='page-item'><a class='page-link' href='owners.php?pagenum=". ($pageNum + 2) . $sortGETURL . $filterByGETURL . "' >" . "Next</a></li>";
		            }
	                
	                ?>
            </ul>
        </nav>
    </div>
</div>

<?php //echo "test"; ?>

</main>

<?php
	
	foreach ($data as $row) {
		$sqlDogs = "SELECT dogs.name FROM dogsOwners INNER JOIN dogs on dogsowners.dogsFk = dogs.id  WHERE dogsowners.ownersFk = " . $row['id'];
		$dogResult = mysqli_query($conn, $sqlDogs);
		
		$sqlCat = "SELECT cats.name FROM catsOwners INNER JOIN cats on catsowners.catsFk = cats.id  WHERE catsowners.ownersFk = " . $row['id'];
		$catResult = mysqli_query($conn, $sqlCat);
		
		$sqlExotic = "SELECT exotics.name FROM exoticsOwners INNER JOIN exotics on exoticsowners.exoticsFk = exotics.id  WHERE exoticsowners.ownersFk = " . $row['id'];
		$exoticsResult = mysqli_query($conn, $sqlExotic);
	
		echo "<div class='modal' id='petsModal" . $row['id'] . "'>";
		echo "  <div class='modal-dialog'>";
		echo "    <div class='modal-content'>";
		echo "      <!-- Modal Header -->";
		echo "      <div class='modal-header'>";
		echo "        <h4 class='modal-title'>Pets for " . $row['fname'] . " " . $row['lname'] . "</h4>";
		echo "        <button type='button' class='close' data-dismiss='modal'>&times;</button>";
		echo "      </div>";
		echo "      <!-- Modal body -->";
		echo "      <div class='modal-body'>";		
		if (mysqli_num_rows($dogResult) > 0){
			echo "<h5>Dog</h5>";
			while($row = mysqli_fetch_assoc($dogResult)) {
				echo "<p style='text-indent: 2em;'>" . $row['name'] . "</p>";
				}
		}
		if (mysqli_num_rows($catResult) > 0){
			echo "<h5>Cat</h5>";
				while($row = mysqli_fetch_assoc($catResult)) {
					echo "<p style='text-indent: 2em;'>" . $row['name'] . "</p>";
				}
		}
		if (mysqli_num_rows($exoticsResult) > 0){
			echo "<h5>Exotic</h5>";
				while($row = mysqli_fetch_assoc($exoticsResult)) {
					echo "<p style='text-indent: 2em;'>" . $row['name'] . "</p>";
				}
		}
		echo "      </div>";
		echo "      <!-- Modal footer -->";
		echo "      <div class='modal-footer'>";
		echo "        <button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>";
		echo "      </div>";
		echo "    </div>";
		echo "  </div>";
		echo "</div>";
	}	
?>

<?php include("../footer.php");?>

<script type="text/javascript">
function filterField(input) {
	
	let url = window.location.pathname + "?" + "filterby=" + input.id + "&filterkey=" + input.value <?php echo " + \"" .$pageGETURL . $sortGETURL . "\";"; ?>
    document.location.href = url;
}
</script>

</body>
</html>
