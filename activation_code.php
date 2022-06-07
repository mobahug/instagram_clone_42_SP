<?php
	require_once './config/setup.php';
	require_once 'comments.inc.php';

	$code = $_GET['code'];
	if(!empty($code) && isset($code))
	{

		$sql = "SELECT `activation_code` FROM `user` WHERE BINARY `activation_code`='$code'";
		//$result = $conn->prepare($sql);
		$result = $conn->query($sql);
		if ($result && $result->fetchColumn())
		{
			echo "Your account has been verified";
			$sql = "UPDATE `user` SET `status`= 1 WHERE `activation_code`='$code'";
			$result = $conn->prepare($sql);
			$result->execute();
			header("Location: index.php");
		}
		else
			echo "You have to verify your account";
	}

?>