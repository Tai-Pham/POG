<?php
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die (error());

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['loginButton']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];

	$username = sanitizeMySQL($conn, $username);
    $password = sanitizeMySQL($conn, $password);
    verifyLogin($username, $password, $conn);
}
$conn->close();
    
function verifyLogin($username, $password, $conn)
{
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
}

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