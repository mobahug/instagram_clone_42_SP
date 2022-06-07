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
				
				$id = $_SESSION['id'];
				//$sql = "SELECT * FROM `user` WHERE `id`=".$id.";";
				$oldsql = "SELECT * FROM `user`
						WHERE `id`=?
						LIMIT 1;";		//THIS HAVE TO CHECK OUT LATER!!!!
				$oldresult = $conn->prepare($oldsql);
				$oldresult->execute(array($id));
				if (!$oldresult)
				{
					echo "SQL statement failed!";
				}
				else
				{
					$rows = $oldresult->fetchAll();
					foreach ($rows as $row)
					{
						if ($row['profilePicture'] != "/128x128.png")
							unlink("profile_images/" . $row['profilePicture']);
					}
				}
				$sql = "SELECT * FROM `user`;";
				$result = $conn->query($sql);
				if (!$result)
				{
					echo "SQL statement failed";
				}
				else
				{
					$username = $_SESSION['id'];
					$sql2 = "UPDATE `user` SET `profilePicture`=? WHERE `id`=?;";
					$result2 = $conn->prepare($sql2);
					if (!$result)
					{
						echo "SQL statement failed";
					}
					else
					{
						$result2->execute(array($imageFullName, $username));
						move_uploaded_file($fileTempName, $fileDestination);
						header("Location: settings.php?user=new_profilepicture_uploaded");
					}
				}
			}
			else
			{
				echo "File size too big";
				header("Location: settings.php?user=errorsize");
			}
		}
		else
		{
			echo "You had an error!";
			header("Location: settings.php?user=perror");
		}
	}
	else
	{
		echo "You need to uppload a proper filetype!";
		header("Location: settings.php?user=notproperfile");
	}
}
?>