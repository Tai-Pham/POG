<?php 
	require_once 'login.php';
	
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
	
	if(isset($_GET['select']) && isset($_GET['input']))
	{
	
		/* Load all relevant content for video */
		$conn = new mysqli($hn, $un, $pw, $db);
		if ($conn->connect_error) die (error());
		
		$id = $_GET['input'];
		$query = "SELECT * FROM videos WHERE videoId = $id";
		
		$result = $conn->query($query);
		if(!$result) die(mysql_fatal_error());
		
		$output = $result->fetch_array(MYSQLI_ASSOC);
	
		$path = "\"" . $output['videoLocation'] . "\"";
		$creator = $output['creator'];
		$title = $output['title'];
		
		/* Comment submission routine */
		if(isset($_POST['commentbox']) && isset($_POST['commentsubmit'])) {
			$commentSanitize = sanitizeMySQL($conn, $_POST['commentbox']);
			$pathSanitize = sanitizeMySql($conn, $path);
		
			$insertstmt = mysqli_prepare($conn, 'INSERT INTO comments(userID, videoLocation, comment, username) VALUES(?,?,?,?)');
			mysqli_stmt_bind_param($insertstmt, 'ssss', $_SESSION['accountid'], $output['videoLocation'], $commentSanitize, $_SESSION['username']);
			mysqli_stmt_execute($insertstmt);
			if(!$insertstmt) die (error() . "<br>");
			else {
				mysqli_stmt_close($insertstmt);
			}
		}
		
		/* Query all comments for the specific video */
		$commentString = "";
		
		$commentQuery = mysqli_query($conn, "SELECT * FROM comments WHERE videoLocation=$path");
		if(!$commentQuery) die(error());
		if($commentQuery->num_rows == 0) {
			$commentString = "No comments, yet.<br>";
		} else {
			for($i = 0; $i < $commentQuery->num_rows; ++$i) {
				$commentQuery->data_seek($i);
				$row = $commentQuery->fetch_array(MYSQLI_ASSOC);
				$dateformat = date_create_from_format('Y-m-d H:i:s', $row['timestamp']);
				$humanDate = date_format($dateformat, 'M d, Y - h:ia');
				
				$commentString = $commentString . $row['username'] . " at " . $humanDate . ":<br>" . $row['comment'] . "<br><br>";
			}
		}
	
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
			.comments{
				font-size: 14px;
				text-align: left;
				margin-left: 200px;
				font-family: Comic Sans MS;
			}
			.comment-title {
				font-size: 20px;
				text-align: left;
				margin-left: 200px;
				font-family: Comic Sans MS;
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
				
				<div class="comments">
					<form method='POST' enctype='multipart/form-data'>
						Leave a comment: <input type="text" name="commentbox" maxlength="140"><br>
					<input type='submit' value='Submit comment' name='commentsubmit'>
				</div>
				<p class="comment-title"><br>Comments:</p>
				<p class="comments">$commentString</p>

			</body>
			
		</html> 
		
_END;

// Sanitizing input
function sanitizeMySQL($conn, $var)
{
	$var = $conn->real_escape_string($var);
	$var = sanitizeString($var);
	return $var;
}

// Sanitizing input
function sanitizeString($var)
{
	$var = stripslashes($var);
	$var = strip_tags($var);
	$var = htmlentities($var);
	return $var;
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
