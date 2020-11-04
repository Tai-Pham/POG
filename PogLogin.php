<?php

echo <<<_END
<style>
  body{
  margin: 0;
  padding: 0;
  font-family: sans-serif;
  background: #34495e;
  text-align: center;
}
.box{
  width: 300px;
  padding: 40px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%,-50%);
  background: #191919;
  text-align: center;
}
.box h1{
  color: white;
  text-transform: uppercase;
  font-weight: 500;
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
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>َPog</title>
  </head>
  <h1> <span style='color:#C5DBFF;font-family:"Comic Sans MS"; font-size:100px;'>POG</h1>
  <body>
<form class="box" action="PogLogin.php" method="post">
  <h1>Login</h1>
  <input type="text" name="username" placeholder="Username">
  <input type="password" name="password" placeholder="Password">
  <input type="submit" name="loginButton" value="Login">
  <input type="submit" name="registerButton" value="Register">
</form>


  </body>
</html>

_END;
/*
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
<input type='text' name='username' style="height:50px; width:300px; font-size:25px;">
Password
<input type = 'password' name = 'password' style="height:50px; width:300px; font-size:25px;">

<input type = 'submit' value = 'Login' name = 'loginButton' style="height:70px; width:150px; font-size:25px;">

<input type = 'submit' value = 'Register' name = 'registerButton' style="height:70px; width:150px; font-size:25px;">
<pre></form></body></html>
_END;*/

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
		echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #191919;
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
		<strong>Missing Field</strong> Please fill out a username.
		</div>

		</body>
		</html>
_END;
	}
	else if (isEmpty($password))
	{
		echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #191919;
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
		<strong>Missing Field</strong> Please fill out a password.
		</div>

		</body>
		</html>
_END;
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