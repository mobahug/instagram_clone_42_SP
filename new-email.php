<?php
	require_once 'config/setup.php';

	$id = $_SESSION['id'];
	$new_email = strip_tags($_POST['new_email']);
	if (strlen($new_email) >= 42)
		header("Location: settings.php?email=toolong_format");
	else if (strlen($new_email) <= 0)
		header("Location: settings.php?email=tooshort_format");
	else
	{
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
		else
			header("Location: settings.php?email=wrong_email");
	}
?>