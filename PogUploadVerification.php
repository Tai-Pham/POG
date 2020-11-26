<?php
require_once 'login.php';

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
		echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #858080;
		color: white;
		}

		.closebtn {
		margin-left: 15px;
		color: white;
		font-weight: bold;
		float: right;
		font-size: 22px;
		line-height: 20px;
		cursor: pointer;
		transition: 0.3s;
		}

		.closebtn:hover {
		color: black;
		}
		</style>
		</head>
		<body>

		<div class="alert">
		<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
		<strong>Error. </strong> Please enter a title.
		</div>

		</body>
		</html>
_END;
    }
    else
    {
        fileUploader($conn);
    }
}

$conn->close();

function fileUploader($conn)
{
	$max_upload_size = 209715200;
	if ($_FILES)
	{
		$size = $_FILES['file']['size'];
		if (!($_FILES['file']['name'])) 
		{
			echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #858080;
		color: white;
		}

		.closebtn {
		margin-left: 15px;
		color: white;
		font-weight: bold;
		float: right;
		font-size: 22px;
		line-height: 20px;
		cursor: pointer;
		transition: 0.3s;
		}

		.closebtn:hover {
		color: black;
		}
		</style>
		</head>
		<body>

		<div class="alert">
		<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
		<strong>Error. </strong> No video has been selected.
		</div>

		</body>
		</html>
_END;
		}
		else if ($size > $max_upload_size)
		{
			echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #858080;
		color: white;
		}

		.closebtn {
		margin-left: 15px;
		color: white;
		font-weight: bold;
		float: right;
		font-size: 22px;
		line-height: 20px;
		cursor: pointer;
		transition: 0.3s;
		}

		.closebtn:hover {
		color: black;
		}
		</style>
		</head>
		<body>

		<div class="alert">
		<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
		<strong>Error. </strong> Files must be 200MB or less.
		</div>

		</body>
		</html>
_END;
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
					$query = "INSERT INTO videos(userID, videoLocation, creator, title, likes, dislikes) VALUES('$unID', '$vidLocation', '$un', '$vidTitle', '$likes', '$likes')";
					mysqli_query($conn, $query);
					echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #8FE86B;
		color: white;
		}

		.closebtn {
		margin-left: 15px;
		color: white;
		font-weight: bold;
		float: right;
		font-size: 22px;
		line-height: 20px;
		cursor: pointer;
		transition: 0.3s;
		}

		.closebtn:hover {
		color: black;
		}
		</style>
		</head>
		<body>

		<div class="alert">
		<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
		<strong>Success. </strong> Video uploaded.
		</div>

		</body>
		</html>
_END;
				}
			}
			else
			{
				echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #858080;
		color: white;
		}

		.closebtn {
		margin-left: 15px;
		color: white;
		font-weight: bold;
		float: right;
		font-size: 22px;
		line-height: 20px;
		cursor: pointer;
		transition: 0.3s;
		}

		.closebtn:hover {
		color: black;
		}
		</style>
		</head>
		<body>

		<div class="alert">
		<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
		<strong>Error. </strong> The selected video is not a valid video file.
		</div>

		</body>
		</html>
_END;
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
