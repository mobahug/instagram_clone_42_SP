<?php

require_once 'config/setup.php';

	if (isset($_POST['reset-request-submit']))
	{
		$selector = bin2hex(random_bytes(8));
		$token = random_bytes(32);
		$url = "http://localhost:8080/IIII/create-new-password.php?selector=" .
					$selector . "&validator=" . bin2hex($token);
		$expires = date("U") + 1800;
		$userEmail = $_POST['email'];

		$sql = "DELETE FROM `pwdReset` WHERE `pwdResetEmail`=?;";
		$result = $conn->prepare($sql);
		
		if (!$result)
		{
			echo "SQL statement failed";
			exit();
		}
		else
		{
			$result = $conn->prepare($sql);
			$result->execute(array($userEmail));
		}


		$sql2 = "INSERT INTO `pwdReset` (`pwdResetEmail`, `pwdResetSelector`, `pwdResetToken`, `pwdResetExpires`) VALUES (?, ?, ?, ?);";
		$result2 = $conn->prepare($sql2);
		if (!$result2)
		{
			echo "SQL statement failed";
			exit();
		}
		else
		{
			$hashedToken = password_hash($token, PASSWORD_DEFAULT);
			$result2 = $conn->prepare($sql2);
			$result2->execute(array($userEmail, $selector, $hashedToken, $expires));
		}
		//close the connection
		//$conn=null;

		$to = $userEmail;

		$subject = "Reset your password";

		$message = "Reset your password2" . PHP_EOL . $url;

		$headers = 'From: gabocza12@gmail.com' . "\r\n" .
						'Reply-To: gabocza12@gmail.com' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
		header("Location: reset-password.php?reset=success");
	}
	else
	{
		header("Location: index.php");
	}