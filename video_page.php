<?php
require_once 'login.php';
	
	define("LIKE_SET", 1);
	define("DISLIKE_SET", 2);
	define("NOLIKE_SET", 0);
	define("INSERT", 0);
	define("UPDATE", 1);
	define("UPDATE_COUNT", 2);
	define("LIKE", 1);
	define("DISLIKE", 2);
	define("LIKE_SEARCH", 0);
	define("LIKE_UNSET", 1);
	define("DISLIKE_UNSET", 2);
	define("NULL_UNSET", 0);
	define("LIKE_ON", "👍 ✅");
	define("DISLIKE_ON", "👎 ✅");
	define("LIKE_OFF", "👍");
	define("DISLIKE_OFF", "👎");

	
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
	
		$path = $output['videoLocation'];
		$creator = $output['creator'];
		$title = $output['title'];
		$likes = $output['likes'] - $output['dislikes'];
		$uID = $_SESSION['accountid'];
		
		$likeDisplay;
		$dislikeDisplay;
		    
		/* 
		    Query like table to check for like/dislikes status
		    0 = unset
		    1 = liked
		    2 = disliked		
		*/
		$likeQuery = "SELECT * FROM likes WHERE videoLocation='$path' AND userID=$uID";
		$likeResult = $conn->query($likeQuery);
		if(!$likeResult) die("Like error: " . $likeResult->error);
		
		$likeOutput = $likeResult->data_seek(0);
		$likeRow = $likeResult->fetch_array(MYSQLI_ASSOC);
		$likeStatus = 0;
		if($likeRow != null) {
		    $likeStatus = $likeRow['likeFlag'];
		}
		
		print_r($_POST);
		
		/* Only allow changing like state if it is unset or the opposite is already checked */
		if(isset($_POST['likeSelect'])) {
			if($likeRow == null) {
				like($conn, $uID, $path, LIKE, INSERT, NULL_UNSET);
			}
		    else if($likeStatus == DISLIKE) {
		        like($conn, $uID, $path, LIKE, UPDATE, DISLIKE_UNSET);
		        like($conn, $uID, $path, LIKE, UPDATE, LIKE_UNSET);
		    }
		    else if($likeStatus == LIKE) {
		    	like($conn, $uID, $path, NOLIKE_SET, UPDATE, LIKE_UNSET);
		    }
		    else if($likeStatus == NOLIKE_SET) {
		    	like($conn, $uID, $path, LIKE, UPDATE, NULL_UNSET);
		    }
		}
		else if(isset($_POST['dislikeSelect'])) {
		    if($likeRow == null) {
		        like($conn, $uID, $path, DISLIKE, INSERT, NULL_UNSET);
		    }
		    else if($likeStatus == LIKE) {
		        like($conn, $uID, $path, DISLIKE, UPDATE, LIKE_UNSET);
		        like($conn, $uID, $path, DISLIKE, UPDATE, DISLIKE_UNSET);
		    }
		    else if($likeStatus == DISLIKE) {
		    	like($conn, $uID, $path, NOLIKE_SET, UPDATE, DISLIKE_UNSET);
		    }
		    else if($likeStatus == NOLIKE_SET) {
		    	like($conn, $uID, $path, DISLIKE, UPDATE, NULL_UNSET);
		    }
		}
		
		$likeStatus == LIKE_SET ? $likeDisplay = LIKE_ON : $likeDisplay = LIKE_OFF;
    	$likeStatus == DISLIKE_SET ? $dislikeDisplay = DISLIKE_ON : $dislikeDisplay = DISLIKE_OFF;
		
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
		
		$commentQuery = mysqli_query($conn, "SELECT * FROM comments WHERE videoLocation='$path'");
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
			.comments{
				font-size: 14px;
				text-align: left;
				margin-left: 200px;
				font-family: Comic Sans MS;
				color: white;
			}
			.comment-title {
				font-size: 20px;
				text-align: left;
				margin-left: 200px;
				font-family: Comic Sans MS;
				color: white;
			}
			.likes{
				text-align: center;
				color: white;
				margin: 5px;
				margin-right: 300px;
				padding: 0;
				font-family:Comic Sans MS;
			}
			.title{ font-size:30px; margin:0; color: white; }
			.creator{ font-size:20px; margin:0 }
			a:hover {
				color: white;
				text-decoration: none;
			}
			body{
				margin: 0;
				padding: 0;
				font-family: sans-serif;
				background: #191919;
				text-align: center;
			  }
			  .box{
				left: 50%;
				background: #191919;
				text-align: center;
			  }
			  .box h1{
				color: white;
				text-transform: uppercase;
				font-size: 25;
				font-weight: 50;
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
				
				<div class='likes'>
		            <form class='likes'>$likes Likes</form>
		            <form class='likes' method='post' enctype='multipart/form-data'>
		            <input style='border:none;background:none' action='video_page.php' type='submit' name='likeSelect' value='$likeDisplay'; />
		            <form class='likes' method='post' enctype='multipart/form-data'>
		            <input style='border:none;background:none' action='video_page.php' type='submit' name='dislikeSelect' value='$dislikeDisplay'; />
		            </form>
            	</div>

				<form class="box" method="post">
					<h1>Leave a comment:</h1>
					<input type="text" name="commentbox" maxlength="140" class="field-comment" placeholder="Comment">
					<input type='submit' value='Submit comment' name='commentsubmit'>
			  	</form>

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

/* 
	Mode:
	0: Insert a like/dislike into the likes table
	1: Update a like/dislike into the likes table
	2: Reflect like count in videos table
	
	Unset:
	1: Unset a like
	2: Unset a dislike
	0: No unset action
*/
function like($conn, $uID, $path, $flag, $mode, $unset) {
	if($mode == INSERT) {
		$likeInsert = mysqli_prepare($conn, 'INSERT INTO likes(userID, videoLocation, likeFlag) VALUES(?, ?, ?)');
		mysqli_stmt_bind_param($likeInsert, 'ssi', $uID, $path, $flag);
		mysqli_execute($likeInsert);
		
		if(!$likeInsert) die (error() . $conn->error(). "<br>");
		else {
			mysqli_stmt_close($likeInsert);
		}
		like($conn, $uID, $path, $flag, UPDATE_COUNT, NULL_UNSET);
	}
	else if($mode == UPDATE) {
		$likeUpdate = mysqli_prepare($conn, 'UPDATE likes SET likeFlag=? WHERE videoLocation=?');
		mysqli_stmt_bind_param($likeUpdate, 'is', $flag, $path);
		mysqli_execute($likeUpdate);
		
		if(!$likeUpdate) die (error() . $conn->error(). "<br>");
		else {
			mysqli_stmt_close($likeUpdate);
		}
		
		if($flag == LIKE || $flag == DISLIKE) {
			like($conn, $uID, $path, $flag, UPDATE_COUNT, NULL_UNSET);
		}
		else if($flag == NOLIKE_SET) {
			if($unset == LIKE_UNSET) {
				like($conn, $uID, $path, $flag, UPDATE_COUNT, LIKE_UNSET);
			} 
			else if($unset == DISLIKE_UNSET) {
				like($conn, $uID, $path, $flag, UPDATE_COUNT, DISLIKE_UNSET);
			}
		}
		else {
			print_r("Invalid flag value! <br>");
		}
	}
	else if($mode == UPDATE_COUNT) {
		$likeCountUpdate;
		if($flag == LIKE || $flag == NOLIKE_SET) {
			if($unset == LIKE_UNSET) {
				$likeCountUpdate = mysqli_prepare($conn, 'UPDATE videos SET likes = likes - 1 WHERE videoLocation=?');
			} else {
				$likeCountUpdate = mysqli_prepare($conn, 'UPDATE videos SET likes = likes + 1 WHERE videoLocation=?');
			}
		}
		else if($flag == DISLIKE || $flag == NOLIKE_SET) {
			if($unset == DISLIKE_UNSET) {
				$likeCountUpdate = mysqli_prepare($conn, 'UPDATE videos SET dislikes = dislikes - 1 WHERE videoLocation=?');
			} else {
				$likeCountUpdate = mysqli_prepare($conn, 'UPDATE videos SET dislikes = dislikes + 1 WHERE videoLocation=?');
			}
		}
		
		mysqli_stmt_bind_param($likeCountUpdate, 's', $path);
		mysqli_execute($likeCountUpdate);
		
		if(!$likeCountUpdate) die (error() . $conn->error(). "<br>");
		else {
			mysqli_stmt_close($likeCountUpdate);
		}
	}
	else {
		print_r("Like mode not specified!<br>");
	}
}

function error()
{
  echo "░░░░░░░░░▄░░░░░░░░░░░░░░▄░░░░<br>";
  echo "░░░░░░░░▌▒█░░░░░░░░░░░▄▀▒▌░░░<br>";
  echo "░░░░░░░░▌▒▒█░░░░░░░░▄▀▒▒▒▐░░░<br>";
  echo "░░░░░░░▐▄▀▒▒▀▀▀▀▄▄▄▀▒▒▒▒▒▐░░░<br>";
  echo "░░░░░▄▄▀▒░▒▒▒▒▒▒▒▒▒█▒▒▄█▒▐░░░<br>";
  echo "░░░▄▀▒▒▒░░░▒▒▒░░░▒▒▒▀██▀▒▌░░░<br>";
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
