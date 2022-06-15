<?php

	require_once 'comments.inc.php';

	if (isset($_POST['deleteUploadImage']) && isset($_POST['gallery_path']))
	{

		$image = stripslashes($_POST['gallery_path']);		//prevent that user delete other user image
															//by copy pasting the picture name on inspect mode

		$sql = "DELETE FROM galleryImages WHERE imgFullNameGallery=? AND userid=?";
		$result = $conn->prepare($sql);
		$result->execute(array($image, $_SESSION['id']));
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