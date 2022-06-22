<?php
	require_once 'comments.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>InstagramClone Profile</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<!-- NAVIGATION BAR -->
	<div class="navbar is-inline-flex is-transparent">
		<div class="navbar-brand">
			<a class="navbar-item" href="homePage.php">
				<img src="./resources/logo2.png" width="85" height="28" alt="InstagramClone">
			</a>
		</div>
		<?php
		if (isset($_SESSION['id']))
		{
			echo '
			<div class="navbar-item is-flex-touch">
				<a class="navbar-item" href="index.php?action=logout">
					<i class="material-icons">logout</i>
				</a>
				<a class="navbar-item">
					<i class="material-icons">favorite_border</i>
				</a>
				<a class="navbar-item">
					<i class="material-icons">person_outline</i>
				</a>
				<a class="navbar-item" href="settings.php">
					<i class="material-icons">settings</i>
				</a>
			</div>
			';
		}
		else
		{
			echo '
			<div class="navbar-item is-flex-touch">
				<a class="navbar-item" href="index.php?action=login">
					<i class="material-icons">login</i>
				</a>
			</div>
			';
		}
		?>
	</div>
	<?php

		if (isset($_SESSION['id']))
		{
			$id = $_SESSION['id'];
			//$sql = "SELECT * FROM `user` WHERE `id`=".$id.";";
			$sql = "SELECT COUNT(`like`.`img`) AS `likeCount`, user.uid AS 'uid',
					user.profilePicture
					FROM `galleryImages`
					LEFT JOIN `like` ON galleryImages.idGallery = `like`.`img`
					INNER JOIN user ON galleryImages.userid = user.id
					WHERE user.id=?
					ORDER BY `upload_date` DESC;";		//THIS HAVE TO CHECK OUT LATER!!!!
			$result = $conn->prepare($sql);
			$result->execute(array($id));
			if (!$result)
			{
				echo "SQL statement failed!";
			}
			else
			{
				$rows = $result->fetchAll();
				foreach ($rows as $row)
				{
					echo '
					<div class="is-fullheight">
						<div class="container">
							<div class="columns body-columns">
								<div class="column is-half is-offset-one-quarter"> <!-- place everything to the middle -->
									<div class="level">
										<div class="level-item has-text-centered">
											<figure class="image is-128x128">
												<img class="is-rounded image is-128x128" src="./profile_images/'.htmlspecialchars($row['profilePicture']).'">
											</figure>
										</div>
										<div class="level-item has-text-centered">
											<div>
												<p class="heading">Username</p>
												<p class="title">'.htmlspecialchars($row['uid']).'</p>
											</div>
										</div>
										<div class="level-item has-text-centered">
											<div>
												<p class="heading">Likes</p>
												<p class="title">'.htmlspecialchars($row['likeCount']).'</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
				}
			}
		}
	?>
	<?php
		if (isset($_SESSION['id']))
		{
			echo '
			<div class="is-fullheight">
				<div class="container">
					<div class="columns body-columns">
						<div class="column is-half is-offset-one-quarter">
							<div class="level">
								<div class="level-item has-text-centered">
									<div class="control">
										<div class="columns is-centered">
											<button type="submit" class="button is-light is-large" onclick="showcamera()">
												<i class="material-icons is-large">camera</i>
											</button>
											&nbsp;
											<button type="submit" class="button is-light is-large" onclick="showcomment()">
												<i class="material-icons is-large">image</i>
											</button>
										</div>
									</div>
								</div>
							</div>';
							if (isset($_GET['usage']))
							{
								if ($_GET['usage'] == "error")
								{
									echo "
									<div class='has-text-centered'>
										<p class='has-text-danger has-background-danger-light'>
											Please take an image and choose a sticker for it!
										</p>
									</div>";
								}
							}
						echo '
						</div>
					</div>
					<div id="camera" style="display:none">
							<div class="columns body-columns">
								<div class="column is-half is-offset-one-quarter">
									<div class="box">
										<video id="video" width="1080" height="1080" autoplay></video>

										<canvas class="output" id="canvas" width="1080" height="1080" value="canvas"></canvas>

										<form class="fotoform" action="add_webcam.php" method="POST" enctype="multipart/form-data">
											<input class="button button-signin" type="submit" name="add" value="4.Add">
											<input type="hidden" id="web_photo" name="new_pic" value="">
											<input type="hidden" id="stamp" name="stamp" value="">
											<input type="hidden" id="stamp3" name="stamp3" value="">
											<input type="hidden" name="camera_date" value="'.date('Y-m-d H:i:s').'">
										</form>

										<button class="button is-light" id="start-camera">1.Start Camera</button>
										<button class="button is-light" id="click-photo">3.Take Photo</button>
										<br>
										<p class="subtitle is-5">2.Choose Sticker</p>

										<button><img class="image is-128x128" onclick="stampPath(this)" src="./stamp/stamp6.png" width="200" height="200"></button>
										<button><img class="image is-128x128" onclick="stampPath(this)" src="./stamp/stamp5.png" width="200" height="200"></button>
										<button><img class="image is-128x128" onclick="stampPath(this)" src="./stamp/stamp3.png" width="200" height="200"></button>
										<button><img class="image is-128x128" onclick="stampPath(this)" src="./stamp/stamp4.png" width="200" height="200"></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>';
		}
	else
		header("Location: index.php?nicetry");
	?>

	<div>
		<?php
			if (isset($_SESSION['id']))
			{
				echo "
				<div class='is-fullheight'>
					<div class='container'>
						<div id='comment' style='display:none'>
							<div class='columns body-columns'>
								<div class='column is-half is-offset-one-quarter'>
									<div class='card'>
										<div class='box gallery-upload'>
											<label class='label has-text-centered'>Upload new picture</label>
											<form action='gallery-upload.inc.php' method='POST' enctype='multipart/form-data'>
												<input class='input' type='text' name='filename' placeholder='File name . . .'>
												<input class='input' type='text' name='filetitle' placeholder='Image title . . .'>
												<input class='input' type='text' name='filedesc' placeholder='Image description . . .'>
												<input class='input' type='file' name='file'>
												<input type='hidden' id='stamp2' name='stamp' value=''>
												<input type='hidden' id='stamp4' name='stamp4' value=''>
												<input type='hidden' name='upload_date' value='".date('Y-m-d H:i:s')."'>
												<button class='button button-signin is-fullwidth' type='submit' name='submitImage'>Upload</button>
											</form>
											<button><img class='image is-128x128' onclick='stampPath(this)' src='./stamp/stamp6.png' width='200' height='200'></button>
											<button><img class='image is-128x128' onclick='stampPath(this)' src='./stamp/stamp5.png' width='200' height='200'></button>
											<button><img class='image is-128x128' onclick='stampPath(this)' src='./stamp/stamp3.png' width='200' height='200'></button>
											<button><img class='image is-128x128' onclick='stampPath(this)' src='./stamp/stamp4.png' width='200' height='200'></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>";
			}
			?>

		<div>
			<div>
				<?php
					$userid = $_SESSION['id'];
					$sql = "SELECT * FROM galleryImages WHERE userid=? ORDER BY upload_date DESC";
					$result = $conn->prepare($sql);
					$result->execute(array($userid));
					if (!$result)
					{
						echo "SQL statement failed!";
					}
					else
					{
						$rows = $result->fetchAll();
						foreach ($rows as $row)
						{
							//outputting image
							echo '
							<div class="is-fullheight">
								<div class="container">
									<div class="columns body-columns">
										<div class="column is-half is-offset-one-quarter">
											<div class="card">
												<div class="card-image">
													<figure class="image is-1by1">
														<img class="curve" src="./user_uploads/'.htmlspecialchars($row["imgFullNameGallery"]).'" alt="'.htmlspecialchars($row['titleGallery']).'">
													</figure>
												</div>
											<div class="level">
												<div class="level-left">
													<form class="form-control" method="POST" action="deleteUploadImage.php">
														<input type="hidden" name="gallery_path" value="'.htmlspecialchars($row["imgFullNameGallery"]).'">
														<button class="button is-hovered" type="submit" name="deleteUploadImage">
															<i class="material-icons">delete</i>
														</button>
													</form>
												</div>
											</div>
											</div>
										</div>
									</div>
								</div>
							</div>';
						}
					}
				?>
			</div>
		</div>
	</div>
	<div class="is-fullheight">
		<div class="container">
			<div class="columns body-columns">
				<div class="column is-half is-offset-one-quarter"> <!-- place everything to the middle -->
					<footer class="footer">
						<div class="container is-fluid">
							<div class="content has-text-centered">
								<p>
									<i><strong>© Mango 2022 </strong> Created by Gabor Ulenius</i>
								</p>
							</div>
						</div>
					</footer>
				</div>
			</div>
		</div>
	</div>
</body>
<script src="hide.js"></script>
<script>
	//camera script
	let camera_button = document.querySelector("#start-camera");
	let video = document.querySelector("#video");
	let click_button = document.querySelector("#click-photo");
	let canvas = document.querySelector("#canvas");
	let new_pic = document.querySelector("#web_photo");
	let final_stamp = document.querySelector("#stamp");
	let final_stamp3 = document.querySelector("#stamp3");
	let final_stamp_upload = document.querySelector("#stamp2");
	let final_stamp4 = document.querySelector("#stamp4");

	let stamp_auth = false;
	let counter = 0;

	function stampPath(element) {
		counter++;
		if (counter == 1)
		{
			final_stamp.value = element.src;
			final_stamp_upload.value = element.src;
		}
		else if (counter == 2)
		{
			final_stamp3.value = element.src;
			final_stamp4.value = element.src;
		}
		else
			return ;
		stamp_auth = true;
	}

	camera_button.addEventListener('click', async function() {
		let stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
		video.srcObject = stream;
	});

	click_button.addEventListener('click', function() {
		if (stamp_auth)
		{
			canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
			let image_data_url = canvas.toDataURL('image/jpeg');
			new_pic.value = image_data_url;
		}
	});
</script>
</html>