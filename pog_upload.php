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
					<a class="Main-Page-Link" href="home.php">POG</a>		
				</h1>
		<form method='post' action='pog_upload.php' enctype='multipart/form-data'>
		<div class ="center">
			Select Video: <input type='file' name='file'>
			<br>
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


	//Create folder directories if they don't exist
	folderCheck();

	if (isset($_POST['Upload']))
	{	
		fileUploader($conn);
	}

	$conn->close();
	}
else{
	header('location: PogLogin.php');
		exit();
}

function fileUploader($conn)
{
	if ($_FILES)
	{
		if (!($_FILES['file']['name'])) 
		{
			echo "No video has been selected. <br>";
		}
		else
		{	
			$vdir = "vidUploads/";
			$txtdir = "txtUploads/";
			$file = $_FILES['file']['name'];
			switch($_FILES['file']['type'])
			{
				case 'video/mp4'		: $ext = 'mp4';	break;
				default					: $ext = ''; 	break;
			}
			
			if ($ext)
			{
				$time = date("Y-m-dh:i:sa");
				$uniquefilename = sanitizeString($time);
				$file = $uniquefilename.basename($_FILES["file"]["name"], $ext).$ext;
				$fname = $vdir.$file;
				$un = $_SESSION['username'];
				if(move_uploaded_file($_FILES["file"]["tmp_name"], $vdir.$file))
				{
					$query = "INSERT INTO videos(videoLocation, creator, title) VALUES('".$fname."', $un,'".$file."')";
					mysqli_query($conn, $query);
					echo "Successful upload";
				}
			}
			else
			{
				echo "The selected video is not .mp4 type. <br>";
			}
		}
	}
}

function folderCheck()
{
	if (!file_exists("vidUploads/"))
	{
		mkdir("vidUploads/");
	}
}

function sanitizeString($var)
{
	$var = str_replace('"', "", $var);
	$var = str_replace(':', "", $var);
	$var = str_replace('-', "", $var);
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
