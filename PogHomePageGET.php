<?php
require_once 'login.php';
require_once 'likeFunc.php';

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
	
	$likeStatus = likeQuery($conn, $path, $uID);
	
	/* Only allow changing like state if it is unset or the opposite is already checked */
	if(isset($_POST['likeSelect'])) {
		if($likeStatus == -1) {
			like($conn, $uID, $path, LIKE, INSERT, NULL_UNSET);
		}
	    else if($likeStatus == DISLIKE) {
	        like($conn, $uID, $path, LIKE, UPDATE, DISLIKE_UNSET);
	        like($conn, $uID, $path, LIKE, UPDATE, LIKE_UNSET);
	    }
	    else if($likeStatus == LIKE) {
	    	like($conn, $uID, $path, NOLIKE_SET, UPDATE, LIKE_UNSET);
	    }
	    else if($likeStatus == NOLIKE_SET) {
	    	like($conn, $uID, $path, LIKE, UPDATE, NULL_UNSET);
	    }
	}
	else if(isset($_POST['dislikeSelect'])) {
	    if($likeStatus == -1) {
	        like($conn, $uID, $path, DISLIKE, INSERT, NULL_UNSET);
	    }
	    else if($likeStatus == LIKE) {
	        like($conn, $uID, $path, DISLIKE, UPDATE, LIKE_UNSET);
	        like($conn, $uID, $path, DISLIKE, UPDATE, DISLIKE_UNSET);
	    }
	    else if($likeStatus == DISLIKE) {
	    	like($conn, $uID, $path, NOLIKE_SET, UPDATE, DISLIKE_UNSET);
	    }
	    else if($likeStatus == NOLIKE_SET) {
	    	like($conn, $uID, $path, DISLIKE, UPDATE, NULL_UNSET);
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
                </form>
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
