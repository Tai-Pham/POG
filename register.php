<?php
echo <<<_END
<style>
body {text-align: center;}
</style>
<html>
<head>
<title> Pog </title>
</head>
<body>
<form method='post' action='register.php' enctype='multipart/form-data'>
<pre>
<span style='color:#C5DBFF;font-family:"Comic Sans MS"; font-size:100px'> 
Username
<input type='text' name='username' style="height:50px; width:300px; font-size:25px;">
Password
<input type = 'password' name = 'password' style="height:50px; width:300px; font-size:25px;">
Repeat Password
<input type = 'password' name = 'repeatedPassword' style="height:50px; width:300px; font-size:25px;">
Email
<input type = 'text' name = 'email' style="height:50px; width:300px; font-size:25px;">

<input type = 'submit' value = 'Register' name = 'registerButton' style="height:70px; width:150px; font-size:25px;">
<pre></form></body></html>
_END;

require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die (error());

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['repeatedPassword']) && isset($_POST['email']) && isset($_POST['registerButton']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$repeatedPassword = $_POST['repeatedPassword'];
	$email = $_POST['email'];

	$username = sanitizeMySQL($conn, $username);
	$password = sanitizeMySQL($conn, $password);
	$repeatedPassword = sanitizeMySQL($conn, $repeatedPassword);
	$email = sanitizeMySQL($conn, $email);
	
	if (isEmpty($username))
	{
		echo "Please fill out a username. <br>";
	}
	else if (searchUsername($conn, $username) == TRUE)
	{
		echo "Username is taken. <br>";
	}
	else if (isEmpty($password))
	{
		echo "Please fill out a password. <br>";
	}
	else if (isEmpty($repeatedPassword))
	{
		echo "Please fill out the repeated password. <br>";
	}
	else if (isEmpty($email))
	{
		echo "Please fill out an email. <br>";
	}
	else if (searchEmail($conn, $email) == TRUE)
	{
		echo "Email is in use. <br>";
	}
	else if ($password != $repeatedPassword)
	{
		echo "The passwords do not match. <br>";
	}
	else
	{
		addUser($conn, $username, $password, $email);
		header('location: authentication.php');
	}
}

// Closing connection
$conn->close();

// 
function searchUsername($conn, $usernameParam)
{
	$search = $conn->prepare('SELECT username FROM login WHERE username = ?');
	$search->bind_param('s', $usernameParam);
	
	$result = FALSE;
	if (!($search->execute()))
	{
		$search->close();
		die (error());
	}
	else
	{
		$search->bind_result($usernameResult);
		
		while ($row = $search->fetch())
		{
			if ($usernameParam == $usernameResult)
			{
				$result = TRUE;
			}
		}
	}
	
	$search->close();
	return $result;
}

// 
function searchEmail($conn, $emailParam)
{
	$search = $conn->prepare('SELECT email FROM login WHERE email = ?');
	$search->bind_param('s', $emailParam);
	
	$result = FALSE;
	
	if (!($search->execute()))
	{
		$search->close();
		die (error());
	}
	else
	{
		$search->bind_result($emailResult);
		
		while ($row = $search->fetch())
		{
			if ($emailParam == $emailResult)
			{
				$result = TRUE;
			}
		}
	}
	
	$search->close();
	return $result;
}

//INSERT INTO `pog`.`login` (`username`, `password`, `hash`, `salt`, `email`) VALUES ('1', '1', '1', '1', '1');
function addUser($connParam, $usernameParam, $passwordParam, $emailParam)
{
	$salt2 = "+W6)#Lcb";
	$salt1 = "@D4K^n3*";
	$passwordParam = hash('ripemd128', $salt1.$passwordParam.$salt2);
	
	$stmt = $connParam->prepare('INSERT INTO login(username, password, salt1, salt2, email) VALUES(?, ?, ?, ?, ?)');
	$stmt->bind_param('sssss', $usernameParam, $passwordParam, $salt1, $salt2, $emailParam);
	
	if (!($stmt->execute()))
	{
		$stmt->close();
		die (error());
	}
	
	$stmt->close();
}

// Sanitizing input
function sanitizeString($var)
{
	$var = stripslashes($var);
	$var = strip_tags($var);
	$var = htmlentities($var);
	return $var;
}

// Sanitizing input
function sanitizeMySQL($conn, $var)
{
	$var = $conn->real_escape_string($var);
	$var = sanitizeString($var);
	return $var;
}

// Checks if field is empty
function isEmpty($string)
{
	$string = trim($string);
	
	if ($string != "")
	{
		return false;
	}
	
	return true;
}

// Error msg
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