<?php
	require_once 'config/setup.php';

	$id = $_SESSION['id'];
	if (isset($_POST['on']))
	{
		$sql = "UPDATE `user` SET `notification_status`=1 WHERE `id`=?";
		$result = $conn->prepare($sql);
		$result->execute(array($id));
		header('Location: settings.php?notification=enabled');
	}
	if (isset($_POST['off']))
	{
		$sql = "UPDATE `user` SET `notification_status`=0 WHERE `id`=?";
		$result = $conn->prepare($sql);
		$result->execute(array($id));
		header('Location: settings.php?notification=disabled');
	}
?>