<?php 
	session_start(); 
?>
<nav class="navbar navbar-expand-sm bg-light navbar-light fixed-top">
    <img class="navbar-brand" src="../img/pawprint.svg" alt="Paws to Care" style="width:40px;">

    <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link <?php if (basename($currentPage, '.php') == 'index'){echo "active"; }?>" href="../index.php">Home</a></li>

        <li class="nav-item"><a class="nav-link <?php if (basename($currentPage, '.php') == 'services'){echo "active"; }?>" href="../services.php">Our Services</a></li>

        <li class="nav-item"><a class="nav-link <?php if (basename($currentPage, '.php') == 'vets'){echo "active"; }?>" href="../vets.php">Meet Our Vets</a></li>

        <li class="nav-item"><a class="nav-link <?php if (basename($currentPage, '.php') == 'contact'){echo "active"; }?>" href="../contact.php">Contact Us</a></li>
    </ul>

    <ul class="navbar-nav ml-auto">
	<?php
		if ($_SESSION['isAdmin']){
			
			if (basename($currentPage, '.php') == 'owners'){
				echo "<li class='nav-item active'><a class='nav-link' href='owners.php'>Owners</a></li>";
			}else{
				echo "<li class='nav-item'><a class='nav-link' href='owners.php'>Owners</a></li>";
			}
			
			if (basename($currentPage, '.php') == 'animals')
			{
				echo "<li class='nav-item dropdown active'>";
			}else{
				echo "<li class='nav-item dropdown'>";
			}
			
			echo "
                <a class='nav-link dropdown-toggle' href='#' id='navbardrop' data-toggle='dropdown'>Animals</a>
                <div class='dropdown-menu'>
                    <a class='dropdown-item' href='../admin/animals.php?animal=dogs'>Dogs</a>
                    <a class='dropdown-item' href='../admin/animals.php?animal=cats'>Cats</a>
                    <a class='dropdown-item' href='../admin/animals.php?animal=exotics'>Exotics</a>
                </div>
            </li>";
		}
		if ($_SESSION['loggedIn']){
			echo "<li class='nav-item'><a class='nav-link' href='../logout.php'>Logout</a></li>";
		}else if (basename($currentPage, '.php') != 'login'){
			echo "<li class='nav-item'><a class='nav-link' href='login.php'>Login</a></li>";
		}

?>
    </ul>
</nav>
