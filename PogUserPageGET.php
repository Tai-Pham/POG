<?php
require_once 'login.php';
require_once 'likeFunc.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die (error());

$name = $_SESSION['username'];

$query = "SELECT * FROM videos WHERE creator='$name'";
$result = $conn->query($query);
if(!$result) die(mysql_fatal_error());

$rows = $result->num_rows;

for($i = $rows - 1; $i >= 0; $i--){
    
    $output = $result->data_seek($i);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    
    $id = $row['videoID'];
    $path = "\"" . $row['videoLocation'] . "\"";
    $creator = $row['creator'];
    $title = $row['title'];
    $likes = $row['likes'];
    $dislikes = $row['dislikes'];
    $uID = $_SESSION['accountid'];
	
	$likeStatus = likeQuery($conn, $path, $uID);
	$likeStatus == LIKE_SET ? $likeDisplay = LIKE_ON : $likeDisplay = LIKE_OFF;
	$likeStatus == DISLIKE_SET ? $dislikeDisplay = DISLIKE_ON : $dislikeDisplay = DISLIKE_OFF;
        
    echo "
            <div class='video-player'>
                <video src=$path width='640' height='360' controls> </video>
            </div>
    
            <div class='title-creator'>
                <a class='title' href='video_page.php?input=$id&select=$title'>$title</a>
                <p class='creator'>Uploaded by: $creator</p>
            </div>	
            
            <div class='likes'>
                <form class='likes'>$likes Likes, $dislikes Dislikes</form>
            </div>
        </body>";
}
	
echo "</html>";

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