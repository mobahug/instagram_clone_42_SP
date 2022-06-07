<?php
	require_once 'config/setup.php';

	$id = $_SESSION['id'];
	$new_email = $_POST['new_email'];
	if (isset($new_email) && isset($_POST['newEmailSubmit']))
	{
		$sql = "SELECT * FROM `user` WHERE `email`=?";
		$result = $conn->prepare($sql);
		$result->execute(array($new_email));
		if ($result && $result->fetchColumn())
		{
			header('Refresh: 0.1; ./settings.php?email=error');
		}
		else
		{
			$sql = "UPDATE `user` SET `email`=? WHERE `id`=?";
			$result = $conn->prepare($sql);
			$result->execute(array($new_email, $id));
			header('Location: settings.php?email=modified');
		}
	}
?>