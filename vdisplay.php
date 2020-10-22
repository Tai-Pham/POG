<?php
echo <<<_END
		<html><head><title>Video Display</title></head></html>		
_END;

require_once 'login2.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die (mysql_fatal_error());

echo "POG <br>";

$query = "SELECT * FROM vidtable";
$result = $conn->query($query);
if(!$result) die(mysql_fatal_error());
$rows = $result->num_rows;

getRows($result, $rows);

$result->close();
$conn->close();

function getRows($result, $rows)
{
	for ($j = 0; $j < $rows; $j++)
	{
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$location = $row['location'];
		echo $row['name']."<br>";
		echo "<video src='".$location."' controls width = '320px' height = '200px' >";
	}
}

function mysql_fatal_error()
{
	$image = 'https://cdn.discordapp.com/emojis/655219185894555648.png?v=1';
	$imageData = base64_encode(file_get_contents($image));
	echo '<img src="data:image/png;base64,'.$imageData.'">';
}
?>
