<?php
	require_once 'comments.inc.php';

	if (isset($_GET['likeButton']))
	{
		$imgId = $_GET['imgId'];
		
		$liker = $_SESSION['id'];

		$sql = "SELECT user FROM `like` WHERE img=? AND user=?";
		$result = $conn->prepare($sql);
		$result->execute(array($imgId, $liker));

		if (!$result->rowCount())
		{
			$sql = "INSERT INTO `like` (user, img) VALUES (?, ?)";
			$result = $conn->prepare($sql);
			$result->execute(array($liker, $imgId));
			header("Location: homePage.php?liked=".$liker);
		}
		else
		{
			$delete_sql = "DELETE FROM `like` WHERE img=? AND user=?";
			$delete_result = $conn->prepare($delete_sql);
			$delete_result->execute(array($imgId, $liker));
			header("Location: homePage.php?disliked=".$liker);
		}
	}
?>