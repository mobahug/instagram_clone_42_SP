<?php
	require_once 'config/setup.php';

	if (isset($_SESSION['id']))
	{
		if (isset($_POST['changePasswordSubmit']) && isset($_POST['change-password']))
		{
			$id = $_SESSION['id'];
			$change_password = $_POST['change-password'];
		
			$uppercase = preg_match('/[A-Z]/', $change_password);
			$lowercase = preg_match('/[a-z]/', $change_password);
			$number = preg_match('/[0-9]/', $change_password);
			$specialChars = preg_match('/[^a-zA-Z\d]/', $change_password);
		
			if (!$uppercase)
			{
				header('Refresh: 0.1; settings.php?password=uppercase');
				return false;
			}
			else if (!$lowercase)
			{
				header('Refresh: 0.1; settings.php?password=lowercase');
				return false;
			}
			else if (!$number)
			{
				header('Refresh: 0.1; settings.php?password=number');
				return false;
			}
			else if (!$specialChars)
			{
				header('Refresh: 0.1; settings.php?password=special');
				return false;
			}
			else if (strlen($change_password) < 8)
			{
				header('Refresh: 0.1; settings.php?password=tooshort');
				return false;
			}
			else if (strlen($change_password) > 50)
			{
				header('Refresh: 0.1; settings.php?password=toolong');
				return false;
			}
			else
			{
				$change_password = hash('whirlpool', $change_password);
				$sql = "UPDATE `user` SET `password`=? WHERE `id`=?";
				$result = $conn->prepare($sql);
				$result->execute(array($change_password, $id));
				header('Refresh: 0.1; settings.php?password=success');
			}
		}
		else
			header("Refresh: 0.1; settings.php?password=wrong_user");
	}
	else
		header("Location: index.php?nicetry");
?>