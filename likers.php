<?php
	date_default_timezone_set('Europe/Helsinki');
	require_once 'comments.inc.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
</head>
<body>
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
				<a class="navbar-item" href="profilePage.php">
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
	<div class='columns body-columns'>
		<div class='column'>
		<?php
		if (isset($_SESSION['id']))
		{
			$sql = "SELECT * FROM `like`
			LEFT JOIN `galleryImages` ON `like`.`img` = galleryImages.idGallery
			INNER JOIN user ON galleryImages.userid = user.id
			GROUP BY `galleryImages`.`idGallery`;";
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
					$sql1 = "SELECT user FROM `like` WHERE img=? AND `user`=?";
					$result1 = $conn->prepare($sql1);
					$result1->execute(array($image_id, $id));
					$rows1 = $result1->fetchAll();
					foreach ($rows1 as $row1)
					{

						//print_r($rows1);
						echo "
						<div class='columns body-columns'>
							<div class='column is-half is-offset-one-quarter'>
								<div class='card'>
									<div class='card-content'>
										<div class='is-text-overflow-parent'>
											<div class='is-text-overflow'>
												<div class='header'>
													<div class='media'>
														<div class='media-left'>
															<figure class='image is-32x32'>
																<img class='is-rounded image is-32x32' src='./profile_images/".htmlspecialchars($row["profilePicture"])."' alt='Placeholder image'>
															</figure>
														</div>
														<div class='media-content'>
															<a href='clicked-user-page.php?user=".htmlspecialchars($row['id'])."'>
																<p class='title is-5'>".htmlspecialchars($row['uid'])."</p>
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						";
					}
				}
			}
		}
		else
			header("Location: index.php?nicetry");
		?>
		</div>
	</div>
</section>
</body>
</html>