<?php
	
	define("LIKE_SET", 1);
	define("DISLIKE_SET", 2);
	define("NOLIKE_SET", 0);
	define("LIKE_SEARCH", 0);
	define("LIKE_ON", "👍 ✅");
	define("DISLIKE_ON", "👎 ✅");
	define("LIKE_OFF", "👍");
	define("DISLIKE_OFF", "👎");

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
			<li><a href="?pog_upload">Upload</a></li>
			<li><a href="?PogLogin">Log Out</a></li>
			</ul>
		</nav>
		</div>
	</header>
_END;
	
	echo<<<_END
		<style>
			.Page-Body{background-color:#64A0FF;}
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
			}
			.likes{
				text-align: left;
				margin: 5px;
				margin-left: 135px;
				padding: 0;
				font-family:Comic Sans MS;
			}
			.title{ font-size:30px; margin:0; padding: 0; }
			.creator{ font-size:20px; margin:0; margin-left: 30px }
	
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
					<a class='Main-Page-Link' href='PogHomePage.php'>POG</a>		
				</h1>
_END;


	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die (error());

	$query = "SELECT * FROM videos";
	$result = $conn->query($query);
	if(!$result) die(mysql_fatal_error());
	
	$rows = $result->num_rows;
	
	for($i = 0; $i < $rows; $i++){
		$output = $result->data_seek($i);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		
		$id = $row['videoID'];
		$path = "\"" . $row['videoLocation'] . "\"";
		$creator = $row['creator'];
		$title = $row['title'];
		$likes = $row['likes'] - $row['dislikes'];
		$uID = $_SESSION['accountid'];
		
		$likeDisplay;
		$dislikeDisplay;
		
		/* 
			Query like table to check for like/dislikes status
			0 = unset
			1 = liked
			2 = disliked		
		*/
		$likeQuery = "SELECT * FROM likes WHERE videoLocation=$path AND userID=$uID";
		$likeResult = $conn->query($likeQuery);
		if(!$likeResult) die("Like error: " . error());
		
		$likeOutput = $likeResult->data_seek(0);
		$likeRow = $likeResult->fetch_array(MYSQLI_ASSOC);
		$likeStatus = $likeRow['likeFlag'];
		
		$likeStatus == LIKE_SET ? $likeDisplay = LIKE_ON : $likeDisplay = LIKE_OFF;
		$likeStatus == DISLIKE_SET ? $dislikeDisplay = DISLIKE_ON : $dislikeDisplay = DISLIKE_OFF;
			
		echo "
				<div class='video-player'>
					<video src=$path width='640' height='360' controls> </video>
				</div>
		
				<div class='title-creator'>
					<form class='title' method='get' action='video_page.php' enctype='multipart/form-data'>
					<input type='hidden' value=$id name='input'>
					<input style='border:none;background:none' type='submit' name='select' value='$title' />
					</form>
					
					<p class='creator'>Uploaded by: $creator</p>
				</div>	
				
				<div class='likes'>
					<form class='likes'>$likes Likes</form>
					<form class='likes' method='get' enctype='multipart/form-data'>
					<input type='hidden' name='like'>
					<input style='border:none;background:none' type='submit' name='likeSelect' value='$likeDisplay'; />
					<form class='likes' method='get' enctype='multipart/form-data'>
					<input type='hidden' name='dislike'>
					<input style='border:none;background:none' type='submit' name='dislikeSelect' value='$dislikeDisplay'; />
					</form>
				</div>
			</body>";
	}
	
	
echo "</html>";

if (isset($_GET['PogLogin']))
{
	session_unset();

	session_destroy();
	header('location: PogLogin.php');
}

if (isset($_GET['pog_upload']))
{
	header('location: pog_upload.php');
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
