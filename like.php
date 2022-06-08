<?php
	require_once 'comments.inc.php';

	if (isset($_POST['liked']) && isset($_POST['userid']))
	{
		$liked = $_POST['liked'];
		$userid = $_POST['userid'];

		$sql = "UPDATE `galleryimages` SET `liked`=1 WHERE userid=?";
		$result = $conn->prepare($sql);
		$result
	}
?>