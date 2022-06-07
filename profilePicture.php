<?php

require_once './config/setup.php';

if (isset($_POST['submitProfile']))
{
	$newFileName = $_POST['filename'];
	if (empty($_POST[$newFileName]))
	{
		$newFileName = "gallery";
	}
	else
	{
		$newFileName = strtolower(str_replace(" ", "-", $newFileName));
	}

	$file = $_FILES['file'];

	$fileName = $file["name"];
	$fileType = $file["type"];
	$fileTempName = $file["tmp_name"];
	$fileError = $file["error"];
	$fileSize = $file["size"];

	$fileExt = explode(".", $fileName);
	$fileActualExt = strtolower(end($fileExt));

	$allowed = array("jpg", "jpeg", "png");

	if (in_array($fileActualExt, $allowed))
	{
		if ($fileError === 0)
		{
			if ($fileSize < 2000000)
			{
				$imageFullName = $newFileName . "." . uniqid("", true) . "." . $fileActualExt;		//uniqueid() true add more character after the file name
				$fileDestination = "./profile_images/" . $imageFullName;
				
				
					$sql = "SELECT * FROM `profileimages`;";
					$result = $conn->query($sql);
					if (!$result)
					{
						echo "SQL statement failed";
					}
					else
					{
						$username = $_SESSION['id'];
						$sql2 = "INSERT INTO `profileimages` (`profileUserId`, `profilePath`) VALUES (?, ?);";
						$result2 = $conn->prepare($sql2);
						if (!$result)
						{
							echo "SQL statement failed";
						}
						else
						{
							$result2->execute(array($username, $imageFullName));
							move_uploaded_file($fileTempName, $fileDestination);
							header("Location: settings.php");
						}
					}
			}
			else
			{
				echo "File size too big";
				exit();
			}
		}
		else
		{
			echo "You had an error!";
			exit();
		}
	}
	else
	{
		echo "You need to uppload a proper filetype!";
		exit();
	}
}
?>