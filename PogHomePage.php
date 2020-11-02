<?php

	// Setting time-out for session
	ini_set('session.gc_maxlifetime', 60*60*24);

	session_start();

	// Checks if a username is inputted
	if (!isset($_SESSION['username']))
	{
		die ("Please <a href='PogLogin.php'> click here </a> to log in.");
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
			
			<body class='Page-Body'> 
	
				<h1 class='POG-Title'> 	
					<a class='Main-Page-Link' href='home.php'>POG</a>		
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
		
		$path = $row['videoLocation'];
		$creator = $row['creator'];
		$title = $row['title'];
			
		echo "
				<div class='video-player'>
					<video src=$path width='640' height='360' controls> </video>
				</div>
		
				<div class='title-creator'>
					<p class='title'>$title</p>
					<p class='creator'>$creator</p>
				</div>	

			</body>";
	}
	
	
echo "</html>";




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
