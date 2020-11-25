<?php
require_once 'login.php';

session_start();

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
			<li><a href="PogHomePage.php">Home</a></li>
			<li><a href="?PogLogin">Log Out</a></li>
			</ul>
		</nav>
		</div>
	</header>
_END;

if (isset($_GET['PogLogin']))
{
	session_unset();
	session_destroy();
	header('location: PogLogin.php');
}

echo <<<_END
		<style>
			.Page-Body{background-color:#191919;}
			.POG-Title{text-align: center;}
			.Main-Page-Link{
				text-decoration:none;
				color:#C5DBFF;
				font-family:Comic Sans MS;
				font-size:100px;
			}
			.center{
				margin:0;
				position: absolute;
				color:white;
					top: 50%;
					left: 50%;
					-ms-transform: translate(-50%, -50%);
						transform: translate(-50%, -50%);
			}
			a:hover {
				color: white;
			}

			body{
				margin: 0;
				padding: 0;
				font-family: sans-serif;
				background: #191919;
				text-align: center;
			  }
			  .box{
				width: 300px;
				padding: 40px;
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%,-50%);
				background: #191919;
				text-align: center;
			  }
			  .box h1{
				color: white;
				text-transform: uppercase;
				font-weight: 500;
			  }
			  .box input[type = "text"],.box input[type = "password"]{
				border:0;
				background: none;
				display: block;
				margin: 20px auto;
				text-align: center;
				border: 2px solid #3498db;
				padding: 14px 10px;
				width: 200px;
				outline: none;
				color: white;
				border-radius: 24px;
				transition: 0.25s;
			  }
			  .box input[type = "text"]:focus,.box input[type = "password"]:focus{
				width: 280px;
				border-color: #2ecc71;
			  }
			  .box input[type = "submit"]{
				border:0;
				background: none;
				display: block;
				margin: 20px auto;
				text-align: center;
				border: 2px solid #2ecc71;
				padding: 14px 40px;
				outline: none;
				color: white;
				border-radius: 24px;
				transition: 0.25s;
				cursor: pointer;
			  }
			  .box input[type = "submit"]:hover{
				background: #2ecc71;
			  }
			  .box input[type = "file"]{
				border:0;
				background: none;
				display: block;
				margin: 20px auto;
				text-align: center;
				border: 2px solid #3498db;
				padding: 14px 40px;
				outline: none;
				color: white;
				border-radius: 24px;
				transition: 0.25s;
				cursor: pointer;
				overflow: hidden;
			  }
			  .box input[type = "file"]:hover{
				background: #3498db;
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
		<body class = "Page-Body">
		<h1 class="POG-Title"> 	
					<a class="Main-Page-Link" href="PogHomePage.php" style="text-decoration: none">POG</a>		
				</h1>
		<form class = "box" method='post' action='PogUpload.php' enctype='multipart/form-data'>
			<div class ="center">
				<input type="file" name="file" class="file-upload" value="Upload">
				<input type="text" name="title" class="field-title" placeholder="Title">
				<input type="submit" name="Upload" class="button-upload" value="Upload">
			</div>
		</form> 
		</body> 
		</html>		
_END;

if(isset($_SESSION['username']))
{
	require_once 'PogUploadVerification.php';
}
else
{
	header('location: PogLogin.php');
	exit();
}
?>