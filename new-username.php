<?php
	require_once 'config/setup.php';

	$id = $_SESSION['id'];
	$new_user = $_POST['new_user'];
	if (isset($new_user) && isset($_POST['newUserSubmit']))
	{
		$sql = "SELECT * FROM `user` WHERE `uid`=?";
		$result = $conn->prepare($sql);
		$result->execute(array($new_user));
		if ($result && $result->fetchColumn())
		{
			header('Refresh: 0.1; ./settings.php?username=error');
		}
		else
		{
			$sql = "UPDATE `user` SET `uid`=? WHERE `id`=?";
			$result = $conn->prepare($sql);
			$result->execute(array($new_user, $id));
			header('Location: settings.php?username=modified');
		}
	}
?>