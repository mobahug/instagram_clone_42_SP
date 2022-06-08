<?php
	require_once './config/setup.php';
	
	if (!empty($_POST['new_pic']) && !empty($_POST['stamp']) && isset($_POST['add']))
	{
		$webcam_photo = $_POST['new_pic'];
		$stamp_path = $_POST['stamp'];
		$username = $_SESSION['id'];
		$liked = 0;

		$date = $_POST['camera_date'];
	

		$webcam_photo = str_replace('data:image/jpeg;base64,', '', $webcam_photo);
		$webcam_photo = str_replace(' ', '+', $webcam_photo);
		$data = base64_decode($webcam_photo);
		$photo_name =  uniqid() . '.jpeg';
		$file = "./user_uploads/" . $photo_name;
		$success = file_put_contents($file, $data);

		$result = $conn->prepare("INSERT INTO `galleryimages` (`userid`, `titleGallery`, `descGallery`, `imgFullNameGallery`, `orderGallery`, `upload_date`, `liked`)
									VALUES (?, ?, ?, ?, ?, ?, ?)");
		$result->execute(array($username, "Title", "Description", $photo_name, "1", $date, $liked));

		$stamp = imagecreatefrompng($stamp_path);
		$resizedStamp = imagescale( $stamp, 200, 200 );

		$img = imagecreatefromjpeg($file);
		
		$margin_r = 10;
		$margin_b = 10;
	
		$sx = imagesx($resizedStamp);	//add height for the image
		$sy = imagesy($resizedStamp);	//add width for the image
	
		imagecopy($img, $resizedStamp, imagesx($img) - $sx - $margin_r, imagesy($img) - $sy - $margin_b, 0, 0, imagesx($resizedStamp), imagesy($resizedStamp));
		header('Content-type: image/png');
		imagejpeg($img, $file, 95);
		imagedestroy($img);
		header('Location: profilePage.php');
	}
	else
	{
		echo "Please take an image and choose a sticker for it! :)" . PHP_EOL;
		header('Refresh: 3; profilePage.php');
	}
?>