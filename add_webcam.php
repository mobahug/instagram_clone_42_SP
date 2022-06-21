<?php
	require_once './config/setup.php';

	if (!empty($_POST['new_pic']) && !empty($_POST['stamp']) && isset($_POST['add']))
	{
		$webcam_photo = $_POST['new_pic'];

		$stamp_path = $_POST['stamp'];
		//$stamp_path2 = "./stamp/stamp3.png";
		$stamp_path2 =  $_POST['stamp3'];

		$username = $_SESSION['id'];
		$date = $_POST['camera_date'];


		$webcam_photo = str_replace('data:image/jpeg;base64,', '', $webcam_photo);
		$webcam_photo = str_replace(' ', '+', $webcam_photo);
		$data = base64_decode($webcam_photo);
		$photo_name =  uniqid() . '.jpeg';
		$file = "./user_uploads/" . $photo_name;
		$success = file_put_contents($file, $data);

		$result = $conn->prepare("INSERT INTO `galleryImages` (`userid`, `titleGallery`, `descGallery`, `imgFullNameGallery`, `orderGallery`, `upload_date`)
									VALUES (?, ?, ?, ?, ?, ?)");
		$result->execute(array($username, "Title", "Description", $photo_name, "1", $date));

		$stamp = imagecreatefrompng($stamp_path);
		$stamp2 = imagecreatefrompng($stamp_path2);


		$resizedStamp = imagescale( $stamp, 480, 320 );
		$resizedStamp2 = imagescale( $stamp2, 480, 320 );

		$img = imagecreatefromjpeg($file);

		$margin_r = -25;
		$margin_b = -40;

		$margin_r2 = 690;
		$margin_b2 = 820;

		$sx = imagesx($resizedStamp);	//add height for the image
		$sy = imagesy($resizedStamp);	//add width for the image

		$sx2 = imagesx($resizedStamp2);
		$sy2 = imagesy($resizedStamp2);

		imagecopy($img, $resizedStamp, imagesx($img) - $sx - $margin_r, imagesy($img) - $sy - $margin_b, 0, 0, imagesx($resizedStamp), imagesy($resizedStamp));

		imagecopy($img, $resizedStamp2, imagesx($img) - $sx2 - $margin_r2, imagesy($img) - $sy2 - $margin_b2, 0, 0, imagesx($resizedStamp2), imagesy($resizedStamp2));

		header('Content-type: image/png');
		imagejpeg($img, $file, 95);
		imagedestroy($img);
		header('Location: profilePage.php');
	}
	else
	{
		header('Location: profilePage.php?usage=error');
	}
?>