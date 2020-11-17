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
<form method='post' action='PogRegister.php' enctype='multipart/form-data'>
<h1> <span style='color:#C5DBFF;font-family:"Comic Sans MS"; font-size:100px'>POG</h1>
<body style="background-color:#64A0FF;">
<pre>
<span style='color:#C5DBFF;font-family:"Comic Sans MS"; font-size:100px'> 
Username
<input type='text' name='username' class="form-control action-username" style="height:50px; width:300px; font-size:25px;">
Password
<input type = 'password' name = 'password' class="form-control action-pass" style="height:50px; width:300px; font-size:25px;">
Repeat Password
<input type = 'password' name = 'repeatedPassword' class="form-control action-reppass" style="height:50px; width:300px; font-size:25px;">
Email
<input type = 'text' name = 'email' class="form-control action-email" style="height:50px; width:300px; font-size:25px;">

<input type = 'submit' value = 'Back' name = 'backButton' class="button-back" style="height:70px; width:150px; font-size:25px;">

<input type = 'submit' value = 'Register' class="button-action" name = 'registerButton' style="height:70px; width:150px; font-size:25px;">
<pre></form></body></html>
_END;

require_once 'login.php';
require_once 'registerVerification.php';

if (isset($_POST['backButton']))
{
	header('location: PogLogin.php');
}
?>
