<?php
//echo <<<_END
//		<style>
//			.Page-Body{background-color:#64A0FF;}
//			.POG-Title{text-align: center;}
//			.Main-Page-Link{
//				text-decoration:none;
//				color:#C5DBFF;
//				font-family:Comic Sans MS;
//				font-size:100px;
//			}
//			.center{
//				margin:0;
//				position: absolute;
//					top: 50%;
//					left: 50%;
//					-ms-transform: translate(-50%, -50%);
//						transform: translate(-50%, -50%);
//			}
//		</style>
//		<html>
//		<head lang="en">
//				<meta charset="UTF-8">
//				<meta meta name="viewport" content="width=device-width, initial-scale=1.0">
//				<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
//				<link rel="icon" type="image/png" href="POG-Favicon.png">
//				<title>POG</title>
//			</head>
//		<body class = "Page-Body">
//		<h1 class="POG-Title"> 	
//					<a class="Main-Page-Link" href="PogHomePage.php">POG</a>		
//				</h1>
//		<form method='post' action='pog_upload.php' enctype='multipart/form-data'>
//		<div class ="center">
//			Select Video: <input type='file' name='file'>
//			<br>
//			Title <input type = 'text' name = 'title' style="height:25px; width:300px; font-size:10px;">
//			<br>
//			<input type='submit' value='Upload' name='Upload'>
//		</div>
//		</form> 
//		</body> 
//		</html>		
//_END;
//
//require_once 'login.php';
//
//session_start();
require_once 'login.php';

session_start();


echo <<<_END
		<style>
			.Page-Body{background-color:#64A0FF;}
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
					top: 50%;
					left: 50%;
					-ms-transform: translate(-50%, -50%);
						transform: translate(-50%, -50%);
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
					<a class="Main-Page-Link" href="PogHomePage.php">POG</a>		
				</h1>
		<form method='post' action='pog_upload.php' enctype='multipart/form-data'>
		<div class ="center">
			Select Video: <input type='file' name='file'>
			<br>
			Title <input type = 'text' name = 'title' style="height:25px; width:300px; font-size:15px;">
			<br>
			<input type='submit' value='Upload' name='Upload'>
		</div>
		</form> 
		</body> 
		</html>		
_END;

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
		background: #3D6AA4;
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
			<li><a href="?Back">Home</a></li>
			<li><a href="?PogLogin">Log Out</a></li>
			</ul>
		</nav>
		</div>
	</header>
_END;

if(isset($_SESSION['username']))
{
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die (mysql_fatal_error());
	
	// Change current working directory
	chdir(getcwd());
	
	//Create folder directories if they don't exist
	folderCheck();
	
	if (isset($_POST['Upload']) && isset($_POST['title']))
	{	
		$title = $_POST['title'];
		$title = sanitizeString($title);
		if (isEmpty($title))
		{
			echo "Please enter a title.";
		}
		else
		{
			fileUploader($conn);
		}
	}

	$conn->close();
}
else{
	header('location: PogLogin.php');
		exit();
}

function fileUploader($conn)
{
	$max_upload_size = 209715200;
	if ($_FILES)
	{
		$size = $_FILES['file']['size'];
		if (!($_FILES['file']['name'])) 
		{
			echo "No video has been selected. <br>";
		}
		if ($size > $max_upload_size)
		{
			echo "Files must be 200MB or less.";
			exit();
		}
		else
		{	
			$vdir = "./vidUploads/";
			$file = $_FILES['file']['name'];
			switch($_FILES['file']['type'])
			{
				case 'video/mp4'		: $ext = 'mp4';	break;
				case 'video/mkv'		: $ext = 'mkv'; break;
				case 'video/webm'		: $ext = 'webm'; break;
				default					: $ext = ''; 	break;
			}
			
			if ($ext)
			{
				$file = basename($_FILES["file"]["name"], $ext).$ext;
				$vidTitle = $_POST['title'];
				$vidLocation = $vdir.$file;
				$un = $_SESSION['username'];
				$unID = $_SESSION['accountid']; // <-------- use session to hold the account Id
							
				if(move_uploaded_file($_FILES["file"]["tmp_name"], $vdir.$file))
				{
					$query = "INSERT INTO videos(userID, videoLocation, creator, title, likes, dislikes) VALUES('$unID', '$vidLocation', '$un', '$vidTitle', '0', '0')";
					mysqli_query($conn, $query);
					
					/* Set permissions */
					chmod($vdir.$file, 0777);
					
					echo "Successful upload";
				}
			}
			else
			{
				echo "The selected video is not a valid video file. <br>";
			}
		}
	}
}

function folderCheck()
{
	if (!file_exists(getcwd() . "/vidUploads/"))
	{
		mkdir(getcwd() . '/vidUploads/', 0777, true);
		chmod(getcwd() . '/vidUploads/', 0777);
	}
}

function isEmpty($string)
{
	$string = trim($string);
	
	if ($string != "")
	{
		return false;
	}
	
	return true;
}

function sanitizeString($var)
{
	$var = stripslashes($var);
	$var = strip_tags($var);
	$var = htmlentities($var);
	return $var;
}

function mysql_fatal_error()
{
	$image = 'https://cdn.discordapp.com/emojis/655219185894555648.png?v=1';
	$imageData = base64_encode(file_get_contents($image));
	echo '<img src="data:image/png;base64,'.$imageData.'">';
}
?>
