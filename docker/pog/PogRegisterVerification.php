<?php
	require_once 'login.php';
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die (error());

	function verifyRegister($username, $password, $repeatedPassword, $email, $conn) {
		if (isEmpty($username))
		{
			http_response_code(503);
			json_encode(array("message" => "Please fill out a username."));
			echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #E65D27;
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
		<strong>Error. </strong> Please fill out a username.
		</div>
		</body>
		</html>
_END;
		}
		else if (searchUsername($conn, $username) == TRUE)
		{
			http_response_code(503);
			json_encode(array("message" => "Username is taken."));
			echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #E65D27;
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
		<strong>Error. </strong> Username is taken.
		</div>
		</body>
		</html>
_END;
		}
		else if (isEmpty($password))
		{
			http_response_code(503);
			json_encode(array("message" => "Please fill out a password."));
			echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #E65D27;
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
		<strong>Error. </strong> Please fill out a password.
		</div>
		</body>
		</html>
_END;
		}
		else if (isEmpty($repeatedPassword))
		{
			http_response_code(503);
			json_encode(array("message" => "Please fill out the repeated password."));
			echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #E65D27;
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
		<strong>Error. </strong> Please fill out the repeated password.
		</div>
		</body>
		</html>
_END;
		}
		else if (isEmpty($email))
		{
			http_response_code(503);
			json_encode(array("message" => "Please fill out an email."));
			echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #E65D27;
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
		<strong>Error. </strong> Please fill out an email.
		</div>
		</body>
		</html>
_END;
		}
		else if (searchEmail($conn, $email) == TRUE)
		{
			http_response_code(503);
			json_encode(array("message" => "Email is in use."));
			echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #E65D27;
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
		<strong>Error. </strong> Email is in use.
		</div>
		</body>
		</html>
_END;
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            http_response_code(503);
			json_encode(array("message" => "Email is not valid."));
			echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #E65D27;
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
		<strong>Error. </strong> Email is not valid.
		</div>
		</body>
		</html>
_END;
        }
		else if ($password != $repeatedPassword)
		{
			http_response_code(503);
			json_encode(array("message" => "The passwords do not match."));
			echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #E65D27;
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
		<strong>Error. </strong> The passwords do not match.
		</div>
		</body>
		</html>
_END;
		}
		else if (strlen($username) > 30)
		{
			http_response_code(503);
			json_encode(array("message" => "Username is too long."));
			echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #E65D27;
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
		<strong>Error. </strong> Username is too long.
		</div>
		</body>
		</html>
_END;
		}
		else if ((strlen($password) < 20) || strlen($password) > 500 )
		{
			http_response_code(503);
			json_encode(array("message" => "Password is not between 20 to 500."));
			echo <<<_END
		<html>
		<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		.alert {
		padding: 20px;
		background-color: #E65D27;
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
		<strong>Error. </strong> Password is not between 20 to 500.
		</div>
		</body>
		</html>
_END;
		}
		else
		{
			http_response_code(201);
			json_encode(array("message" => "User creation successful."));
			
			addUser($conn, $username, $password, $email);
			$userID = getID($conn, $username);
			addAccount($conn, $userID);

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
		<strong>Success. </strong> Account created.
		</div>
		</body>
		</html>
_END;
		}
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
	
	// Adds a USERID inside ACCOUNT and sets following and follwers to 0
	function addAccount($connParam, $userIDParam)
	{
		$following = 0;
		$followers = 0;
		$stmt = $connParam->prepare('INSERT INTO account(userID, following, followers) VALUES(?, ?, ?)');
		$stmt->bind_param('sss', $userIDParam, $following, $followers);
		
		if (!($stmt->execute()))
		{
			$stmt->close();
			die (error());
		}
		$stmt->close();
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
		
		verifyRegister($username, $password, $repeatedPassword, $email, $conn);
	}
	
	// Closing connection
	$conn->close();
	
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
