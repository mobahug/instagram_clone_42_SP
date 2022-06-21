<?php

require_once './config/setup.php';

if (isset($_POST['submitImage']))
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
	$imageTitle = $_POST['filetitle'];
	$imageDesc = $_POST['filedesc'];

	$file = $_FILES['file'];

	$fileName = $file["name"];
	$fileType = $file["type"];
	$fileTempName = $file["tmp_name"];
	$fileError = $file["error"];
	$fileSize = $file["size"];

	$fileExt = explode(".", $fileName);
	$fileActualExt = strtolower(end($fileExt));

	$allowed = array("jpg", "jpeg", "png");


	$stamp_path = $_POST['stamp'];
	$stamp_path3 =  $_POST['stamp3'];


	if (in_array($fileActualExt, $allowed))
	{
		if ($fileError === 0)
		{
			if ($fileSize < 2000000)
			{
				$imageFullName = $newFileName . "." . uniqid("", true) . "." . $fileActualExt;		//uniqueid() true add more character after the file name
				$fileDestination = "./user_uploads/" . $imageFullName;

				if (empty($imageTitle) || empty($imageDesc))
				{
					header("Location: index.php?upload=empty");
					exit();
				}
				else
				{
					$sql = "SELECT * FROM `galleryImages`;";
					$result = $conn->query($sql);
					if (!$result)
					{
						echo "SQL statement failed";
					}
					else
					{
						$rowCount = $result->fetchColumn();
						$setImageOrder = $rowCount + 1;

						$username = $_SESSION['id'];
						$upload_date = $_POST['upload_date'];
						$liked = 0;

						$sql2 = "INSERT INTO `galleryImages` (`userid`, `titleGallery`, `descGallery`, `imgFullNameGallery`, `orderGallery`, `upload_date`) VALUES (?, ?, ?, ?, ?, ?);";
						$result2 = $conn->prepare($sql2);
						if (!$result)
						{
							echo "SQL statement failed";
						}
						else
						{
							$result2->execute(array($username, $imageTitle, $imageDesc, $imageFullName, $setImageOrder, $upload_date));
							move_uploaded_file($fileTempName, $fileDestination);
							header("Location: profilePage.php");
						}
					}
				}
				if (isset($stamp_path))
				{
					$stamp = imagecreatefrompng($stamp_path);
					$resizedStamp = imagescale($stamp, 480, 320 );
					if ($fileActualExt == "png")
						$img = imagecreatefrompng($fileDestination);
					else
						$img = imagecreatefromjpeg($fileDestination);

					$margin_r = -25;
					$margin_b = -40;

					$sx = imagesx($resizedStamp);	//add height for the image
					$sy = imagesy($resizedStamp);	//add width for the image

					imagecopy($img, $resizedStamp, imagesx($img) - $sx - $margin_r, imagesy($img) - $sy - $margin_b, 0, 0, imagesx($resizedStamp), imagesy($resizedStamp));
					header('Content-type: image/png');
					imagejpeg($img, $fileDestination, 95);
					imagedestroy($img);
				}
				if (isset($stamp_path) && isset($stamp_path3))
				{
					$stamp = imagecreatefrompng($stamp_path);
					$stamp3 = imagecreatefrompng($stamp_path3);


					$resizedStamp = imagescale($stamp, 480, 320 );
					$resizedStamp3 = imagescale($stamp3, 480, 320 );

					var_dump($stamp_path3);
					if ($fileActualExt == "png")
						$img = imagecreatefrompng($fileDestination);
					else
						$img = imagecreatefromjpeg($fileDestination);

					$margin_r = -25;
					$margin_b = -40;

					$margin_r3 = -50;
					$margin_b3 = -40;

					$sx = imagesx($resizedStamp);	//add height for the image
					$sy = imagesy($resizedStamp);	//add width for the image

					$sx3 = imagesx($resizedStamp3);	//add height for the image
					$sy3 = imagesy($resizedStamp3);	//add width for the image

					imagecopy($img, $resizedStamp, imagesx($img) - $sx - $margin_r, imagesy($img) - $sy - $margin_b, 0, 0, imagesx($resizedStamp), imagesy($resizedStamp));

					imagecopy($img, $resizedStamp2, imagesx($img) - $sx3 - $margin_r3, imagesy($img) - $sy3 - $margin_b3, 0, 0, imagesx($resizedStamp3), imagesy($resizedStamp3));

					header('Content-type: image/png');
					imagejpeg($img, $fileDestination, 95);
					imagedestroy($img);
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