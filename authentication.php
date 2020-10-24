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
<form method='post' action='authentication.php' enctype='multipart/form-data'>
<pre>
<span style='color:#C5DBFF;font-family:"Comic Sans MS"; font-size:100px'> 
Username
<input type='text' name='username' style="height:50px; width:300px; font-size:25px;">
Password
<input type = 'password' name = 'password' style="height:50px; width:300px; font-size:25px;">

<input type = 'submit' value = 'Login' name = 'loginButton' style="height:70px; width:150px; font-size:25px;">

<input type = 'submit' value = 'Register' name = 'registerButton' style="height:70px; width:150px; font-size:25px;">

<pre>
</form>
</body>
</html>
_END;

require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die (error());

// Log in handling
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['loginButton']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];

	$username = sanitizeMySQL($conn, $username);
	$password = sanitizeMySQL($conn, $password);
	
	if (isEmpty($username))
	{
		echo "Please fill out a username. <br>";
	}
	else if (isEmpty($password))
	{
		echo "Please fill out a password. <br>";
	}
	else
	{
		$loginStatus = checkUser($conn, $username, $password);
		
		if ($loginStatus == TRUE)
		{
			header('location: ');
		}
		else
		{
			die ("Incorrect username or password. <br>");
		}
		
	}
}

// Register Handling
if (isset($_POST['registerButton']))
{
	echo "s";
}

// Closing connection
$conn->close();

// Checks if username is in database
function checkUser($connParam, $usernameParam, $passwordParam)
{
	$exists = FALSE;
	
	$search = $connParam->prepare('SELECT * FROM users WHERE username = ?');
	$search->bind_param('s', $usernameParam);
	
	if (!($search->execute()))
	{
		$search->close();
		die (error());
	}
	else
	{
		$search->bind_result($username, $salt1, $salt2, $password);
		
		$hasUsername = "";
		$s1;
		$s2;
		$token;
		
		while ($row = $search->fetch())
		{
			$hasUsername = $username;
			$s1 = $salt1;
			$s2 = $salt2;
			$token = $password;
		}
		
		if (isEmpty($hasUsername))
		{
			$exists = FALSE;
		}
		else
		{
			$passwordParam = hash('ripemd128', $s1.$passwordParam.$s2);
			
			if ($passwordParam == $token)
			{
				$exists = TRUE;
			}
		}
	}
	$search->close();
	
	return $exists;
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