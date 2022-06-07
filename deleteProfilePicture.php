<?php

	require_once 'comments.inc.php';

	if (isset($_POST['deleteProfileImage']) && isset($_POST['profilePath']))
	{

		$image = stripslashes($_POST['profilePath']);		//prevent that user delete other user image
															//by copy pasting the picture name on inspect mode
		$username = $_SESSION['id'];
		$defaultimage = "/128x128.png";
		$sql = "UPDATE `user` SET `profilePicture`=? WHERE `id`=?;";
		$result = $conn->prepare($sql);
		$result->execute(array($defaultimage, $_SESSION['id']));
		if ($result->rowCount())
			unlink("profile_images/" . $image);	//delete image from user_uploads too
		header("Location: settings.php?deletedProfileImage");
	}
	else
	{
		echo "Something went wrong during the image deleting process";
		header('Refresh: 1.8; ./settings.php');
	}
?>