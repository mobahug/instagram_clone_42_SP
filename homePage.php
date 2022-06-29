<?php
	require_once 'comments.inc.php';
	require_once 'gallery-upload.inc.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>mangofruit.fi</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="navbar is-inline-flex is-transparent">
		<div class="navbar-brand">
			<a class="navbar-item">
				<img src="./resources/logo2.png" width="85" height="28" alt="InstagramClone">
			</a>
		</div>
		<div class="navbar-item is-flex-touch">
			<?php
				if (isset($_SESSION['id']))
				{
					echo '
						<a class="navbar-item" href="homePage.php">
							<i class="material-icons has-text-success">home</i>
						</a>
						<a class="navbar-item" href="index.php?action=logout">
							<i class="material-icons">logout</i>
						</a>
						<a class="navbar-item">
							<i class="material-icons">favorite_border</i>
						</a>
						<a class="navbar-item" href="profilePage.php">
							<i class="material-icons">person_outline</i>
						</a>';
				}
				else
				{
					echo '
						<a class="navbar-item" href="homePage.php">
							<i class="material-icons has-text-success">home</i>
						</a>
						<a class="navbar-item" href="index.php?action=logout">
							<i class="material-icons">login</i>
						</a>';
				}
			?>
		</div>
	</div>
	<div>
		<!-- PHP/HTML for upload picture -->
		<?php
			/* $sql = "SELECT * FROM `galleryimages`
					INNER JOIN user ON galleryimages.userid = user.id
					ORDER BY `upload_date` DESC"; */
			$sql = "SELECT COUNT(`like`.`img`) AS `likeCount`, user.id AS 'id', user.uid AS 'uid',
					user.profilePicture AS profilePicture,
					galleryImages.userid AS userid,
					galleryImages.imgFullNameGallery AS imgFullNameGallery, galleryImages.titleGallery AS titleGallery,
					galleryImages.descGallery AS descGallery,
					galleryImages.upload_date AS upload_date,
					galleryImages.idGallery AS idGallery
					FROM `galleryImages`
					LEFT JOIN `like` ON galleryImages.idGallery = `like`.`img`
					INNER JOIN user ON galleryImages.userid = user.id
					GROUP BY `galleryImages`.`idGallery`
					ORDER BY `upload_date` DESC;";
			$result = $conn->prepare($sql);
			$result->execute();
			if (!$result)
			{
				echo "SQL statement failed!";
			}
			else
			{
				$rows = $result->fetchAll();

				foreach ($rows as $row)
				{
					//print_r($row);
					$image_id = $row['idGallery'];
					$sql1 = "SELECT user FROM `like` WHERE img='$image_id'";
					$result1 = $conn->prepare($sql1);
					$result1->execute();
					$rows1 = $result1->fetchAll();
					echo '
				<div class="is-fullheight">
					<div class="container">
						<div class="columns body-columns">
							<div class="column is-half is-offset-one-quarter">
								<div class="card">
									<div class="header">
										<div class="media">
											<div class="media-left">
												<figure class="image is-48x48">
													<a href="clicked-user-page.php?user='.htmlspecialchars($row['id']).'">
														<img class="is-rounded image is-48x48" src="./profile_images/'.htmlspecialchars($row['profilePicture']).'" alt="Placeholder image">
													</a>
												</figure>
											</div>
											<div class="media-content">
												<a href="clicked-user-page.php?user='.htmlspecialchars($row['id']).'">
													<p class="title is-4">'.htmlspecialchars($row['uid']).'</p>
												</a>
												<p class="subtitle is-6">@'.htmlspecialchars($row['uid']).'</p>
											</div>
										</div>
									</div>
									<div class="card-image">
										<figure class="image is-1by1">
											<img class="curve" src="./user_uploads/'.htmlspecialchars($row["imgFullNameGallery"]).'" alt="'.htmlspecialchars($row['titleGallery']).'">
										</figure>
									</div>
									<div class="card-content">
										<div class="level is-mobile">
											<div class="level-left">
												<div class="level-item has-text-centered">';
												$liked = 0;
												if (isset($_SESSION['id']))
												{
													foreach($rows1 as $row1)
													{
														if ($row1['user'] == $_SESSION['id'])
															$liked = 1;
													}
													if ($liked == 1)
													{
														echo '
														<a href="like.php?likeButton=1&imgId='.htmlspecialchars($row['idGallery']).'">
															<i onclick="ajaxLike('.$row['idGallery'].')" name="dislike" id="'.htmlspecialchars($row['idGallery']).'-heart" class="has-text-danger material-icons">favorite_border</i>
														</a>';
													}
													else
													{
														echo '
														<a name="like" href="like.php?likeButton=1&imgId='.htmlspecialchars($row['idGallery']).'">
															<i onclick="ajaxLike('.$row['idGallery'].')" name="like" id="'.htmlspecialchars($row['idGallery']).'-heart" class="has-text-grey-dark material-icons">favorite_border</i>
														</a>';
													}
												}
												else
													echo 'You need to be logged in to like!';
												echo '</div>
												<div class="level-item has-text-centered">
													<div>
														<button class="button is-white" onclick="showcamera('.htmlspecialchars($row['idGallery']).')">
															<i class="has-text-grey-dark material-icons">chat_bubble_outline</i>
														</button>
													</div>
												</div>
											</div>
										</div>
										<div class="content">
											<a href="likers.php?likes='.htmlspecialchars($row['idGallery']).'">
												<p>
													<strong>'.htmlspecialchars($row['likeCount']).' Likes</strong>
												</p>
											</a>
											<p class="title is-5">'.htmlspecialchars($row["titleGallery"]).'</p>
											<p class="subtitle is-6">'.htmlspecialchars($row["descGallery"]).'</p>
											<a>@mangofruit</a>
											<a href="#">#mango</a>
											<a href="#">#socialmedia</a>
											<a href="#">#platform</a>
											<a href="#">#fromskretch</a>
											<br>
											<p class="subtitle is-6">'.htmlspecialchars($row['upload_date']).'</p>
										</div>
									</div>
								</div>
							</div>
						</div>';
					if (isset($_SESSION['id']))
					{

						echo	"
							<div id='camera".htmlspecialchars($row['idGallery'])."' style='display:none'>
								<div class='columns body-columns'>
									<div class='column'>
										<div class='card'>
											<div class='card-content'>
												<div class='content'>
													<form id='postForm' class='box' method='POST' action='homePage.php?action=setComments'>
														<input type='hidden' id='uid' name='uid' value='".$_SESSION['id']."'>
														<input type='hidden' id='date' name='date' value='".date('Y-m-d H:i:s')."'>
														<input type='hidden' id='imgid' name='imgid' value='".htmlspecialchars($row['idGallery'])."'>
														<textarea maxlength='1500' class='textarea' placeholder='Add a comment . . .' name='message'></textarea><br>
														<input class='button is-hovered' type='submit' name='commentSubmit' value='Comment'></input>
													</form>
												</div>
												<div class='message is-success'>
													<p>You are logged in!</p>
												</div>
											</div>
										</div>";
					}
					else
					{
						echo "
							<div id='camera".htmlspecialchars($row['idGallery'])."' style='display:none'>
								<div class='columns body-columns'>
									<div class='column is-half is-offset-one-quarter'>
										<div class='card'>
											<div class='card-content'>
												<div class='content'>
													<p>You need to be logged in to comment!</p>
												</div>
											</div>
										</div>
									</div>
								</div>";
					}
					getComments($conn, $row['idGallery']);
					echo '
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
<script>
	function showcamera(id)
	{
		let comment = document.getElementById('camera'+id);
		if (comment.style.display == 'block'){
			comment.style.display = 'none';
		} else {
			comment.style.display = 'block';
		}
	}

	function ajaxLike(imageId){

		let xml = new XMLHttpRequest();
		let imageHeart = document.getElementById(imageId+'-heart');
		let status = imageHeart.name;

		xml.open('post', 'like.php', true);
		xml.setRequestHeader("content-type", "application/x-www-form-urlencoded");

		if (status == 'like'){
			xml.send('like=1&image_heart='+imageId+'&heart_status=like');
			imageHeart.name = 'dislike';
		}

		if (status == 'dislike'){
			xml.send('like=1&image_heart='+imageId+'&heart_status=dislike');
			imageHeart.name = 'like';
		}
	}



/* 	document.getElementById('postForm').addEventListener('submit', postName);
	function postName(e) {
		e.preventDefault();

		var uid = document.getElementById('uid').value;
		var date = document.getElementById('date').value;
		var imgid = document.getElementById('imgid').value;

		var params = "name="+uid + "name="+date + "name="+imgid;

		var xhr = new XMLHttpRequest();

		xhr.open('POST', 'homePage.php?action=setComments', true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')

		xhr.onload = function() {
			console.log(this.responseText);
			document.getElementById("txtHint").innerHTML=this.responseText;
		}
		xhr.send(params);
	} */
</script>
</html>
