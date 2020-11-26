<?php
require_once 'login.php';

define("LIKE_SET", 1);
define("DISLIKE_SET", 2);
define("NOLIKE_SET", 0);
define("LIKE_SEARCH", 0);
define("LIKE_ON", "👍 ✅");
define("DISLIKE_ON", "👎 ✅");
define("LIKE_OFF", "👍");
define("DISLIKE_OFF", "👎");

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die (error());

$query = "SELECT * FROM videos";
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
    $likes = $row['likes'] - $row['dislikes'];
    $uID = $_SESSION['accountid'];
    
    $likeDisplay;
	$dislikeDisplay;
        
    /* 
        Query like table to check for like/dislikes status
        0 = unset
        1 = liked
        2 = disliked		
    */
    $likeQuery = "SELECT * FROM likes WHERE videoLocation=$path AND userID=$uID";
    $likeResult = $conn->query($likeQuery);
    if(!$likeResult) die("Like error: " . $likeResult->error);
    
    $likeOutput = $likeResult->data_seek(0);
    $likeRow = $likeResult->fetch_array(MYSQLI_ASSOC);
    $likeStatus = 0;
    if($likeRow != null) {
        $likeStatus = $likeRow['likeFlag'];
    }
    
    /* Only allow changing like state if it is unset or the opposite is already checked */
    if(isset($_POST['likeSelect'])) {
        if($likeStatus == 2) {
            
        }
        else if($likeRow == NULL) {
            likeInsert($conn, $uID, $path, 1);
        }
    }
    else if(isset($_POST['dislikeSelect'])) {
        if($likeStatus == 1) {
            
        }
        else if($likeRow == NULL) {
            likeInsert($conn, $uID, $path, 2);
        }
    }
    
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
                <form class='likes'>$likes Likes</form>
                <form class='likes' method='post' enctype='multipart/form-data'>
                <input type='hidden' name='like'>
                <input style='border:none;background:none' type='submit' name='likeSelect' value='$likeDisplay'; />
                <form class='likes' method='post' enctype='multipart/form-data'>
                <input type='hidden' name='dislike'>
                <input style='border:none;background:none' type='submit' name='dislikeSelect' value='$dislikeDisplay'; />
                </form>
            </div>
        </body>";
        
    $likeResult->close();
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
