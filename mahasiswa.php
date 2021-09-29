<?php 
	session_start(); 
	if(!isset($_SESSION["mahasiswa"]))
	{
  	header("Location: login.php");
  	exit;
}
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mahasiswa</title>
	<link rel="stylesheet" type="text/css" href="style-web.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

	<!-- header -->
	<div class="medsos">
		<div class="container">
			<ul>
				<li><a href="#"><i class="fab fa-facebook"></i></a></li>
				<li><a href="#"><i class="fab fa-instagram"></i></a></li>
				<li><a href="#"><i class="fab fa-youtube"></i></a></li>
			</ul>
		</div>
	</div>
	<header>
		<div class="container">
		<h1><a href="index.html"></a>KPL</h1>
		<ul>
			<li class="active"><a href="index.php">HOME</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
		</div>
	</header>

	<!-- banner -->
	<section class="banner">
		<h2>WELCOME TO MY WEBSITE</h2>
	</section>


</body>
</html>