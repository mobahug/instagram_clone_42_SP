<?php
require_once 'config/setup.php';

	if (isset($_POST['reset-password-submit']))
	{
		$selector = $_POST['selector'];
		$validator = $_POST['validator'];
		$password = $_POST['pwd'];
		$passwordRepeat = $_POST['pwd-repeat'];

		if (empty($password) || empty($passwordRepeat))
		{
			header("Location: reset-password.php?newpwd=empty");
			die();
		}
		else if ($password != $passwordRepeat)
		{
			header("Location: reset-password.php?newpwd=pwdnotsame");
			die();
		}
		$currentDate = date("U");

		$sql = "SELECT * FROM `pwdReset` WHERE `pwdResetSelector`=? AND `pwdResetExpires` >= ?;";
		$result = $conn->prepare($sql);
		if (!$result)
		{
			echo "SQL statement failed";
			die();
		}
		else
		{
			$result = $conn->prepare($sql);
			$result->execute(array($selector, $currentDate));

			if (!$row = $result->fetch())
			{
				echo "You need to resubmit your reset request!";
				die();
			}
			else
			{
				$tokenBin = hex2bin($validator);
				$tokenCheck = password_verify($tokenBin, $row['pwdResetToken']);

				if ($tokenCheck === false)
				{
					echo "You need to resubmit your reset request!";
					die();
				}
				else if ($tokenCheck === true)
				{
					$tokenEmail = $row['pwdResetEmail'];

					$sql2 = "SELECT * FROM `user` WHERE `email`=?;";
					$result2 = $conn->prepare($sql2);
					if (!$result2)
					{
						echo "SQL statement failed";
						die();
					}
					else
					{
						$result2 = $conn->prepare($sql2);
						/* $resul2->bind_param("s", $tokenEmail); */
						$result2->execute(array($tokenEmail));
						if (!$row2 = $result2->fetch())
						{
							echo "There was an eror!";
							die();
						}
						else
						{
							$sql3 = "UPDATE `user` SET password=? WHERE `email`=?;";
							$result3 = $conn->prepare($sql3);
							if (!$result3)
							{
								echo "SQL statement failed";
								die();
							}
							else
							{
								$newPwdHash = hash('whirlpool', $password);
								$result3 = $conn->prepare($sql3);
								/* $resul2->bind_param("s", $tokenEmail); */
								$result3->execute(array($newPwdHash, $tokenEmail));

								$sql4 = "DELETE FROM `pwdReset` WHERE `pwdResetEmail`=?;";
								$result4 = $conn->prepare($sql4);
								if (!$result4)
								{
									echo "SQL statement failed";
									die();
								}
								else
								{
									$result4 = $conn->prepare($sql4);
									/* $resul2->bind_param("s", $tokenEmail); */
									$result4->execute(array($tokenEmail));

									header("Location: index.php?newpwd=passwordupdated");
								}
							}
						}
					}
				}
			}
		}
	}
	else
	{
		header("Location: index.php");
	}
?>