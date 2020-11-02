<?php 
	require_once 'login.php';
	
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
		header('location: home.php');
		exit();
	}
	
	echo<<<_END
	
		<!DOCTYPE html>

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

			<body class="Page-Body"> 
	
				<h1 class="POG-Title"> 	
					<a class="Main-Page-Link" href="PogHomePage.php">POG</a>		
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