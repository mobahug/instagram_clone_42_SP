<?php

	require_once 'comments.inc.php';

	if (isset($_POST['deleteUploadImage']) && isset($_POST['gallery_path']) && isset($_POST['gallery_id']))
	{
		$image = stripslashes($_POST['gallery_path']);		//prevent that user delete other user image
															//by copy pasting the picture name on inspect mode
		$id = $_SESSION['id'];
		$imgid = $_POST['gallery_id'];

		$sql_like = "DELETE FROM `like` WHERE `user`=? AND `img`=?";
		$result_like = $conn->prepare($sql_like);
		$result_like->execute(array($id, $imgid));

		$sql_comment = "DELETE FROM `comments` WHERE `uid`=? AND `imgid`=?";
		$result_comment = $conn->prepare($sql_comment);
		$result_comment->execute(array($id, $imgid));


		$sql = "DELETE FROM `galleryImages` WHERE `userid`=? AND `imgFullNameGallery`=?";
		$result = $conn->prepare($sql);
		$result->execute(array($id, $image));
		if ($result->rowCount())
			unlink("user_uploads/" . $image);	//delete image from user_uploads too
		header("Location: profilePage.php?deletedUploadImage");
	}
	else
	{
		echo "Something went wrong during the image deleting process";
		header('Refresh: 1.8; ./profilePage.php');
	}
?>