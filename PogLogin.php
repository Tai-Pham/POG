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
<h1> <span style='color:#C5DBFF;font-family:"Comic Sans MS"; font-size:100px'>POG</h1>
<body style="background-color:#64A0FF;">
<form method='post' action='PogLogin.php' enctype='multipart/form-data'>
<pre>
<span style='color:#C5DBFF;font-family:"Comic Sans MS"; font-size:100px'> 
Username
<input type='text' name='username' class="form-control field-username" style="height:50px; width:300px; font-size:25px;">
Password
<input type = 'password' name = 'password' class="form-control field-pass" style="height:50px; width:300px; font-size:25px;">

<input type = 'submit' value = 'Login' name = 'loginButton' class="button-login" style="height:70px; width:150px; font-size:25px;">

<input type = 'submit' value = 'Register' name = 'registerButton' class="button-register" style="height:70px; width:150px; font-size:25px;">
<pre></form></body></html>
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
			session_start();
			$_SESSION['username'] = $username;
			$_SESSION['accountid'] = getID($conn, $username);
			$_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']);
			header('location: PogHomePage.php');
		}
		else
		{
			$_SESSION['logIn'] = FALSE;
			die ("Incorrect username or password.<br>");
		}
		
	}
}

// Register Handling
if (isset($_POST['registerButton']))
{
	header('location: PogRegister.php');
}

// Closing connection
$conn->close();

// Checks if username is in database
function checkUser($connParam, $usernameParam, $passwordParam)
{
	$exists = FALSE;
	
	$search = $connParam->prepare('SELECT username, password, salt1, salt2 FROM login WHERE username = ?');
	$search->bind_param('s', $usernameParam);
	
	if (!($search->execute()))
	{
		$search->close();
		die (error());
	}
	else
	{
		$search->bind_result($username, $password, $salt1, $salt2);
		
		$hasUsername = "";
		$s1;
		$s2;
		$token;
		
		while ($row = $search->fetch())
		{
			$hasUsername = $username;
			$token = $password;
			$s1 = $salt1;
			$s2 = $salt2;
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

// Grabs the login's USERID
function getID($connParam, $usernameParam)
{
	$userID = 0;
	$search = $connParam->prepare('SELECT userID FROM login WHERE username = ?');
	$search->bind_param('s', $usernameParam);

	if (!($search->execute()))
	{
		$search->close();
		die (error());
	}
	else
	{
		$search->bind_result($userIDResult);
		
		while ($row = $search->fetch())
		{
			$userID = $userIDResult;
		}
	}

	return $userID;
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
