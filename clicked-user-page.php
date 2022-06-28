<?php
	require_once 'comments.inc.php';
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
	<!-- <link rel="stylesheet" href="style.css"> -->
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
				<a class="navbar-item" href="homePage.php">
					<i class="material-icons">home</i>
				</a>
				<a class="navbar-item" href="index.php?action=logout">
					<i class="material-icons">logout</i>
				</a>
				<a class="navbar-item">
					<i class="material-icons">favorite_border</i>
				</a>
				<a class="navbar-item" href="profilePage.php">
					<i class="material-icons">person_outline</i>
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
		if (!isset($_SESSION['id']))
			header("Location: index.php");
		else if ($_GET['user'] == $_SESSION['id'])
			header("Location: profilePage.php");
		else
		{
			$id = $_GET['user'];
			$sql = "SELECT COUNT(`like`.`img`) AS `likeCount`, user.uid AS 'uid',
					user.profilePicture
					FROM `galleryImages`
					LEFT JOIN `like` ON galleryImages.idGallery = `like`.`img`
					INNER JOIN user ON galleryImages.userid = user.id
					WHERE user.id=?
					ORDER BY `upload_date` DESC;";
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
					$sql2 = "SELECT user.uid AS 'uid',
								COUNT(galleryimages.userid) AS `postCount`
								FROM `galleryimages`
								INNER JOIN user ON galleryimages.userid = user.id
								WHERE user.id=?;";
					$result2 = $conn->prepare($sql2);
					$result2->execute(array($id));
					$results = $result2->fetchAll();
					$postCount= $results[0]['postCount'];
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
												<p class="heading">Posts</p>
												<p class="title">'.htmlspecialchars($postCount).'</p>
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
	<div>
		<div>
			<div>
				<?php
					$sql = "SELECT * FROM galleryImages WHERE userid=".$_GET['user']." ORDER BY upload_date DESC";
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
							//outputting image
							echo '
							<div class="is-fullheight">
								<div class="container">
									<div class="columns body-columns">
										<div class="column is-half is-offset-one-quarter">
											<div class="card">
												<div class="card-image">
													<figure class="image is-1by1">
														<img src="./user_uploads/'.htmlspecialchars($row["imgFullNameGallery"]).'" alt="'.htmlspecialchars($row['titleGallery']).'">
													</figure>
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
</html>