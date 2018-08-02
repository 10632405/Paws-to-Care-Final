<?php
$currentPage = __FILE__;
?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Paws to Care - Contact Us</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" sizes="16x16">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>

</head>

<body>

<?php include("nav.php");?>

  <div id="homeBanner" class="jumbotron text-center mt-2 container">
    <h1>Paws to Care</h1>

</div>

<main role="main" class="container text-center">
<div class="m-2">
    You can call us between 8 a.m - 7 p.m. Monday - Saturday
    <br>
    <br>
    Our office is located at
    <br>
    123 Fake St.
    <br>
    Provo, Utah 84604
    <div class="row">
	    <div class="col-sm-4"></div>
	    <div class="col-sm-4 googleMapBox">
	    Google Maps!
	    </div>
	</div>
	<div class="col-sm-4"></div>
    
    <br>
    <br>
    You can follow us on social media!
    <br>
    <br>

    <a class="btn btn-social-icon btn-facebook"><span class="fa fa-facebook" style="font-size: 35pt"></span></a>
    <a class="btn btn-social-icon btn-google"><span class="fa fa-google" style="font-size: 35pt"></span></a>
    <a class="btn btn-social-icon btn-instagram"><span class="fa fa-instagram" style="font-size: 35pt"></span></a>
    <a class="btn btn-social-icon btn-twitter"><span class="fa fa-twitter" style="font-size: 35pt"></span></a>

    
</div>
</main>

<?php include("footer.php");?>

</body>
</html>
