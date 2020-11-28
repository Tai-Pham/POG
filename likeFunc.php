<?php
require_once 'login.php';

define("LIKE_SET", 1);
define("DISLIKE_SET", 2);
define("NOLIKE_SET", 0);
define("INSERT", 0);
define("UPDATE", 1);
define("UPDATE_COUNT", 2);
define("LIKE", 1);
define("DISLIKE", 2);
define("LIKE_SEARCH", 0);
define("LIKE_UNSET", 1);
define("DISLIKE_UNSET", 2);
define("NULL_UNSET", 0);
define("LIKE_ON", "👍 ✅");
define("DISLIKE_ON", "👎 ✅");
define("LIKE_OFF", "👍");
define("DISLIKE_OFF", "👎");

function likeQuery($conn, $path, $uID) {
	/* 
	    Query like table to check for like/dislikes status
	    0 = unset
	    1 = liked
	    2 = disliked		
	*/
	$likeQuery = "SELECT * FROM likes WHERE videoLocation='$path' AND userID=$uID";
	$likeResult = $conn->query($likeQuery);
	if(!$likeResult) die("Like error: " . $likeResult->error);
	
	$likeOutput = $likeResult->data_seek(0);
	$likeRow = $likeResult->fetch_array(MYSQLI_ASSOC);
	$likeRow != null ? $likeFlag = $likeRow['likeFlag'] : $likeFlag = -1;
	return $likeFlag;
}

/* 
	Mode:
	0: Insert a like/dislike into the likes table
	1: Update a like/dislike into the likes table
	2: Reflect like count in videos table
	
	Unset:
	1: Unset a like
	2: Unset a dislike
	0: No unset action
*/
function like($conn, $uID, $path, $flag, $mode, $unset) {
	if($mode == INSERT) {
		$likeInsert = mysqli_prepare($conn, 'INSERT INTO likes(userID, videoLocation, likeFlag) VALUES(?, ?, ?)');
		mysqli_stmt_bind_param($likeInsert, 'ssi', $uID, $path, $flag);
		mysqli_execute($likeInsert);
		
		if(!$likeInsert) die (error() . $conn->error(). "<br>");
		else {
			mysqli_stmt_close($likeInsert);
		}
		like($conn, $uID, $path, $flag, UPDATE_COUNT, NULL_UNSET);
	}
	else if($mode == UPDATE) {
		$likeUpdate = mysqli_prepare($conn, 'UPDATE likes SET likeFlag=? WHERE videoLocation=?');
		mysqli_stmt_bind_param($likeUpdate, 'is', $flag, $path);
		mysqli_execute($likeUpdate);
		
		if(!$likeUpdate) die (error() . $conn->error(). "<br>");
		else {
			mysqli_stmt_close($likeUpdate);
		}
		
		if($flag == LIKE || $flag == DISLIKE) {
			like($conn, $uID, $path, $flag, UPDATE_COUNT, NULL_UNSET);
		}
		else if($flag == NOLIKE_SET) {
			if($unset == LIKE_UNSET) {
				like($conn, $uID, $path, $flag, UPDATE_COUNT, LIKE_UNSET);
			} 
			else if($unset == DISLIKE_UNSET) {
				like($conn, $uID, $path, $flag, UPDATE_COUNT, DISLIKE_UNSET);
			}
		}
		else {
			print_r("Invalid flag value! <br>");
		}
	}
	else if($mode == UPDATE_COUNT) {
		$likeCountUpdate;
		if($flag == LIKE) {
			if($unset == LIKE_UNSET) {
				$likeCountUpdate = mysqli_prepare($conn, 'UPDATE videos SET likes = likes - 1 WHERE videoLocation=?');
			} else {
				$likeCountUpdate = mysqli_prepare($conn, 'UPDATE videos SET likes = likes + 1 WHERE videoLocation=?');
			}
		}
		else if($flag == DISLIKE) {
			if($unset == DISLIKE_UNSET) {
				$likeCountUpdate = mysqli_prepare($conn, 'UPDATE videos SET dislikes = dislikes - 1 WHERE videoLocation=?');
			} else {
				$likeCountUpdate = mysqli_prepare($conn, 'UPDATE videos SET dislikes = dislikes + 1 WHERE videoLocation=?');
			}
		}
		else if($flag == NOLIKE_SET) {
			if($unset == LIKE_UNSET) {
				$likeCountUpdate = mysqli_prepare($conn, 'UPDATE videos SET likes = likes - 1 WHERE videoLocation=?');
			}
			else if($unset == DISLIKE_UNSET) {
				$likeCountUpdate = mysqli_prepare($conn, 'UPDATE videos SET dislikes = dislikes - 1 WHERE videoLocation=?');
			}
		}
		
		$path = str_replace('"', '', $path);
		mysqli_stmt_bind_param($likeCountUpdate, 's', $path);
		mysqli_execute($likeCountUpdate);
		
		if(!$likeCountUpdate) die (error() . $conn->error(). "<br>");
		else {
			mysqli_stmt_close($likeCountUpdate);
		}
	}
	else {
		print_r("Like mode not specified!<br>");
	}
}

?>
