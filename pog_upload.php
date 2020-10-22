<?php
echo <<<_END
		<html><head><title>Video Upload</title></head>
		<body>
		<form method='post' action='vUpload.php' enctype='multipart/form-data'>
		Select Video: <input type='file' name='file'>
		<br>
		<br>
		<input type='submit' value='Upload' name='Upload'>
		</form> </body> </html>		
_END;

require_once 'login2.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die (mysql_fatal_error());

//Create folder directories if they don't exist
folderCheck();

if (isset($_POST['Upload']))
{	
	fileUploader($conn);
}

$conn->close();

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
				if(move_uploaded_file($_FILES["file"]["tmp_name"], $vdir.$file))
				{
					$query = "INSERT INTO vidtable(name,location) VALUES('".$file."','".$fname."')";
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