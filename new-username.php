<?php
	require_once 'config/setup.php';

	$id = $_SESSION['id'];
	$new_user = strip_tags($_POST['new_user']);
	if (strlen($new_user) >= 15)
		header("Location: settings.php?username=toolong_username");
	else if (strlen($new_user) <= 0)
		header("Location: settings.php?username=tooless");
	else
	{
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
		else
			header("Location: settings.php?username=wrong_user");
			//finish later
	}
?>