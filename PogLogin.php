<?php

echo <<<_END
<style>
  body{
  margin: 0;
  padding: 0;
  font-family: sans-serif;
  background: #191919;
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
  <input type="text" name="username" class="form-control field-username" placeholder="Username">
  <input type="password" name="password" class="form-control field-pass" placeholder="Password">
  <input type="submit" name="loginButton" class="button-login" value="Login">
  <input type="submit" name="registerButton" class="button-register" value="Register">
</form>
</body>
</html>
_END;
require_once 'PogLoginVerification.php';

// Register Handling
if (isset($_POST['registerButton']))
{
	header('location: PogRegister.php');
}
?>