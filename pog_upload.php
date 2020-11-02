<?php
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
			Title <input type = 'text' name = 'title' style="height:25px; width:300px; font-size:10px;">
			<br>
			<input type='submit' value='Upload' name='Upload'>
		</div>
		</form> 
		</body> 
		</html>		
_END;

require_once 'login.php';

session_start();

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
			$vdir = "vidUploads/";
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
				$likes = 0; // <--------- likes will always start at zero
							
				if(move_uploaded_file($_FILES["file"]["tmp_name"], $vdir.$file))
				{
					$query = "INSERT INTO videos(userID, videoLocation, creator, title, likes) VALUES('$unID', '$vidLocation', '$un', '$vidTitle', '$likes')";
					mysqli_query($conn, $query);
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
		mkdir('vidUploads/');
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