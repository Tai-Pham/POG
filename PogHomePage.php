<?php

require_once 'login.php';

// Setting time-out for session
ini_set('session.gc_maxlifetime', 60*60*24);

session_start();

// Checks if a username is inputted
if (!isset($_SESSION['username']))
{
	header('location: PogLogin.php');
	exit();
}

// Session Security
if (!isset($_SESSION['check']) && $_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']))
{
	different_user();
}

if (!isset($_SESSION['initiated'])) 
{
	session_regenerate_id();
	$_SESSION['initiated'] = 1;
}

$name = $_SESSION['username'];

function likeInsert($conn, $uID, $path, $flag) {
	$likeInsert = mysqli_prepare($conn, 'INSERT INTO likes(userID, videoLocation, likeFlag) VALUES(?, ?, ?)');
	mysqli_stmt_bind_param($likeInsert, 'ssi', $uID, $path, $flag);
	mysqli_execute($likeInsert);
	
	if(!$likeInsert) die (error() . $conn->error(). "<br>");
	else {
		mysqli_stmt_close($likeInsert);
	}
}

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
		<li><a href="?Name">$name</a></li>
		<li><a href="?Upload">Upload</a></li>
		<li><a href="?PogLogin">Log Out</a></li>
		</ul>
	</nav>
	</div>
</header>
_END;
	
echo<<<_END
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
			color: white;
			margin-right: 550px;
			font-family:Comic Sans MS;		
		}
		.likes{
			text-align: center;
			color: white;
			margin: 5px;
			margin-right: 300px;
			padding: 0;
			font-family:Comic Sans MS;
		}
		.title{ font-size:30px; 
			margin:0; 
			padding: 0; 
			text-decoration:none;
			color: white;
			font-family:Comic Sans MS;}
		.creator{ 
			font-size:20px; 
			margin:0; 
			margin-left: 30px 
		}

		a:hover {
			color: white;
		}	
	</style>
	<html>
		<head lang="en">
			<meta charset="UTF-8">
			<meta meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
			<link rel="icon" type="image/png" href="POG-Favicon.png">
			<title>POG</title>
		</head>
		
		<body class='Page-Body'> 

			<h1 class='POG-Title'> 	
				<a class='Main-Page-Link' href='PogHomePage.php' style="text-decoration: none">POG</a>		
			</h1>
_END;

if (isset($_GET['Upload']))
{
	header('location: PogUpload.php');
}

if (isset($_GET['Name']))
{
	header('location: PogUserPage.php');
}

if (isset($_GET['PogLogin']))
{
	session_unset();
	session_destroy();
	header('location: PogLogin.php');
}
require_once 'PogHomePageGET.php'
?>
