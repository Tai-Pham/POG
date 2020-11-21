<?php 
	require_once 'login.php';
	session_start();
	
	if(isset($_GET['select']) && isset($_GET['input']))
	{		
		$conn = new mysqli($hn, $un, $pw, $db);
		if ($conn->connect_error) die (error());
		
		$id = $_GET['input'];
		$query = "SELECT * FROM videos WHERE videoId = $id";
		
		$result = $conn->query($query);
		if(!$result) die(mysql_fatal_error());
		
		$output = $result->fetch_array(MYSQLI_ASSOC);
	
		$path = $output['videoLocation'];
		$creator = $output['creator'];
		$title = $output['title'];
	
	
		$result->close();
		$conn->close();

	}
	else{
		header('location: PogHomePage.php');
		exit();
	}

	$name = $_SESSION['username'];

echo<<<_END
<style>
url('https://fonts.googleapis.com/css?family=Work+Sans:400,600');
body {
	margin: 100;
	background: #222;
	font-family: 'Work Sans', sans-serif;
	font-weight: 800;
}

.container {
	max-width: 100%;
	float: right;
	height: 60px;
	margin: 0 auto;
}

header {
	background: #202933;
}

header::after {
	content: '';
	display: table;
	clear: both;
}

nav {
	clear: both;
	float: right;
}

nav ul {
	margin: 0;
	padding: 0;
	list-style: none;
}

nav li {
	display: inline-block;
	margin-left: 70px;
	padding-top: 23px;

	position: relative;
}

nav a {
	color: white;
	text-decoration: none;
	text-transform: uppercase;
	font-size: 14px;
}

nav a:hover {
	color: white;
	text-decoration: none
}

nav a::before {
	content: '';
	display: block;
	height: 5px;
	background-color: white;

	position: absolute;
	top: 0;
	width: 0%;

	transition: all ease-in-out 250ms;
}

nav a:hover::before {
	width: 100%;
}
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<html>
<header>
	<div class="container">
	<nav>
		<ul>
		<li><a href="#">$name</a></li>
		<li><a href="?Back">Back</a></li>
		<li><a href="?PogLogin">Log Out</a></li>
		</ul>
	</nav>
	</div>
</header>
_END;
	
	echo<<<_END
	
		<!DOCTYPE html>

		<style>
			.Page-Body{background-color:#191919;}
			.POG-Title{text-align: center;}
			.Main-Page-Link{
				text-decoration:none;
				color:#C5DBFF;
				font-family:Comic Sans MS;
				font-size:100px;
			}
			.video-player{
			margin-top: 30px; 
			text-align: center;
			}
			.title-creator{
				text-align: center;
				margin-right: 550px;
				font-family:Comic Sans MS;
				color: white;
			}
			.title{ font-size:30px; margin:0 }
			.creator{ font-size:20px; margin:0 }
	
		</style>


		<html>
			<head lang="en">
				<meta charset="UTF-8">
				<meta meta name="viewport" content="width=device-width, initial-scale=1.0">
				<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
				<link rel="icon" type="image/png" href="POG-Favicon.png">
				<title>POG</title>
			</head>

			<body class="Page-Body"> 
	
				<h1 class="POG-Title"> 	
					<a class="Main-Page-Link" href="home.php">POG</a>		
				</h1>
		
				<!-- width="640" height="360" for now --> 
				<div class="video-player">
					<video src=$path width="640" height="360" controls> </video>
				</div>
		
				<div class="title-creator">
					<p class="title">$title</p>
					<p class="creator">$creator</p>
				</div>	

			</body>
			
		</html> 
		
_END;

if (isset($_GET['Back']))
{
	header('location: PogHomePage.php');
}

if (isset($_GET['PogLogin']))
{
	session_unset();
	session_destroy();
	header('location: PogLogin.php');
}

function error()
{
  echo "░░░░░░░░░▄░░░░░░░░░░░░░░▄░░░░<br>";
  echo "░░░░░░░░▌▒█░░░░░░░░░░░▄▀▒▌░░░<br>";
  echo "░░░░░░░░▌▒▒█░░░░░░░░▄▀▒▒▒▐░░░<br>";
  echo "░░░░░░░▐▄▀▒▒▀▀▀▀▄▄▄▀▒▒▒▒▒▐░░░<br>";
  echo "░░░░░▄▄▀▒░▒▒▒▒▒▒▒▒▒█▒▒▄█▒▐░░░<br>";
  echo "░░░▄▀▒▒▒░░░▒▒▒░░░▒▒▒▀██▀▒▌░░░ <br>";
  echo "░░▐▒▒▒▄▄▒▒▒▒░░░▒▒▒▒▒▒▒▀▄▒▒▌░░<br>";
  echo "░░▌░░▌█▀▒▒▒▒▒▄▀█▄▒▒▒▒▒▒▒█▒▐░░<br>";
  echo "░▐░░░▒▒▒▒▒▒▒▒▌██▀▒▒░░░▒▒▒▀▄▌░<br>";
  echo "░▌░▒▄██▄▒▒▒▒▒▒▒▒▒░░░░░░▒▒▒▒▌░<br>";
  echo "▀▒▀▐▄█▄█▌▄░▀▒▒░░░░░░░░░░▒▒▒▐░<br>";
  echo "▐▒▒▐▀▐▀▒░▄▄▒▄▒▒▒▒▒▒░▒░▒░▒▒▒▒▌<br>";
  echo "▐▒▒▒▀▀▄▄▒▒▒▄▒▒▒▒▒▒▒▒░▒░▒░▒▒▐░<br>";
  echo "░▌▒▒▒▒▒▒▀▀▀▒▒▒▒▒▒░▒░▒░▒░▒▒▒▌░<br>";
  echo "░▐▒▒▒▒▒▒▒▒▒▒▒▒▒▒░▒░▒░▒▒▄▒▒▐░░<br>";
  echo "░░▀▄▒▒▒▒▒▒▒▒▒▒▒░▒░▒░▒▄▒▒▒▒▌░░<br>";
  echo "░░░░▀▄▒▒▒▒▒▒▒▒▒▒▄▄▄▀▒▒▒▒▄▀░░░<br>";
  echo "░░░░░░▀▄▄▄▄▄▄▀▀▀▒▒▒▒▒▄▄▀░░░░░<br>";
  echo "░░░░░░░░░▒▒▒▒▒▒▒▒▒▒▀▀░░░░░░░░<br>";
}
?>