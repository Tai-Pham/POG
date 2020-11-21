<?php
	require_once 'login.php';
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die (error());
	
	function verifyRegister($username, $password, $repeatedPassword, $email, $conn) {
		if (isEmpty($username))
		{
			http_response_code(503);
			echo json_encode(array("message" => "Please fill out a username."));
			// echo "Please fill out a username. <br>";
		}
		else if (searchUsername($conn, $username) == TRUE)
		{
			http_response_code(503);
			echo json_encode(array("message" => "Username is taken."));
			// echo "Username is taken. <br>";
		}
		else if (isEmpty($password))
		{
			http_response_code(503);
			echo json_encode(array("message" => "Please fill out a password."));
			// echo "Please fill out a password. <br>";
		}
		else if (isEmpty($repeatedPassword))
		{
			http_response_code(503);
			echo json_encode(array("message" => "Please fill out the repeated password."));
			// echo "Please fill out the repeated password. <br>";
		}
		else if (isEmpty($email))
		{
			http_response_code(503);
			echo json_encode(array("message" => "Please fill out an email."));
			// echo "Please fill out an email. <br>";
		}
		else if (searchEmail($conn, $email) == TRUE)
		{
			http_response_code(503);
			echo json_encode(array("message" => "Email is in use."));
			// echo "Email is in use. <br>";
		}
		else if ($password != $repeatedPassword)
		{
			http_response_code(503);
			echo json_encode(array("message" => "The passwords do not match."));
			echo "The passwords do not match. <br>";
		}
		else
		{
			http_response_code(201);
			echo json_encode(array("message" => "User creation successful."));
			
			addUser($conn, $username, $password, $email);
			$userID = getID($conn, $username);
			addAccount($conn, $userID);
			header('location: PogLogin.php');
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