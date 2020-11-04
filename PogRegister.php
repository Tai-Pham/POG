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
<form class="box" action="PogRegister.php" method="post">
  <h1>Register</h1>
  <input type="text" name="username" placeholder="Username">
  <input type="password" name="password" placeholder="Password">
  <input type="password" name="repeatedPassword" placeholder="Repeated Password">
  <input type="password" name="email" placeholder="Email">
  <input type="submit" name="registerButton" value="Register">
  <input type="submit" name="backButton" value="Back">
</form>


  </body>
</html>
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
	else if (searchUsername($conn, $username) == TRUE)
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
		<strong>In use. </strong> Username is taken.
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
	else if (isEmpty($repeatedPassword))
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
		<strong>Missing Field</strong> Please fill out a repeated password.
		</div>

		</body>
		</html>
_END;
	}
	else if (isEmpty($email))
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
		<strong>Missing Field</strong> Please fill out an email.
		</div>

		</body>
		</html>
_END;
	}
	else if (searchEmail($conn, $email) == TRUE)
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
		<strong>In use! </strong> Email is in use.
		</div>

		</body>
		</html>
_END;
	}
	else if ($password != $repeatedPassword)
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
		<strong>Error</strong> Passwords do not match.
		</div>

		</body>
		</html>
_END;
	}
	else
	{
		addUser($conn, $username, $password, $email);
		$userID = getID($conn, $username);
		addAccount($conn, $userID);
		header('location: PogLogin.php');
	}
}

if (isset($_POST['backButton']))
{
	header('location: PogLogin.php');
}

// Closing connection
$conn->close();

// Searching is username is taken
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

// Search is email is taken
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

// Adds user information into LOGIN
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

// Adds a USERID inside ACCOUNT and sets following and follwers to 0
function addAccount($connParam, $userIDParam)
{
	$following = 0;
	$followers = 0;
	$stmt = $connParam->prepare('INSERT INTO account(userID, following, followers) VALUES(?, ?, ?)');
	$stmt->bind_param('iii', $userIDParam, $following, $followers);
	
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