<?php
	require 'comments.inc.php';
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
</head>
<body>
	<div class="navbar is-inline-flex is-transparent">
		<div class="navbar-brand">
			<a class="navbar-item" href="homePage.php">
				<img src="./resources/instagram-text-logo-83656.png" width="112" height="28" alt="InstagramClone">
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
	<div class="columns body-columns">
		<div class="column is-half is-offset-one-quarter"> <!-- place everything to the middle -->
			<div class="container is-fluid">
				<?php
					if (isset($_SESSION['id']))
					{
						echo '
						<div class="box has-text-centered">
							<button class="button">
								<a href="settings.php?action=delete">Delete Account</a>
							</button>
							<br><br>
							<div class="field">
								<form action="notification.php" method="POST">
									<input class="button is-light" type="submit" name="on" value="On">
									<input class="button is-light" type="submit" name="off" value="Off">
								</form>
						';
						$id = $_SESSION['id'];
						$sql2 = "SELECT * FROM `user` WHERE `id`='$id'";
						$stmt = $conn->query($sql2);
						$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
						if ($result[0]['notification_status'] == 1)
						{
							echo "<p class='has-text-success has-background-success-light'>Notification is ON!</p>";
						}
						if ($result[0]['notification_status'] == 0)
						{
							echo "<p class='has-text-danger has-background-danger-light'>Notification is OFF</p>";
						}
					}
						echo '
						<br>
						<div>
							<p>Change username</p>';
							if (isset($_GET['username']))
							{
								if ($_GET['username'] == "modified")
								{
									echo "<p class='has-text-success has-background-success-light'>Sucessfully updated username!</p>";
								}
								if ($_GET['username'] == "error")
								{
									echo "<p class='has-text-danger has-background-danger-light'>Username already in use!</p>";
								}
							}

						echo '
							<form action="new-username.php" method="POST">
								<input class="input" type="text" name="new_user" placeholder="New Username">
								<br><br>
								<input class="button is-primary" type="submit" name="newUserSubmit">
							</form>
						</div>
						';

						echo '
						<br><br>
						<div>
							<p>Change email</p>';
							if (isset($_GET['email']))
							{
								if ($_GET['email'] == "modified")
								{
									echo "<p class='has-text-success has-background-success-light'>Sucessfully updated email!</p>";
								}
								if ($_GET['email'] == "error")
								{
									echo "<p class='has-text-danger has-background-danger-light'>Email already in use!</p>";
								}
							}
						echo '
							<form action="new-email.php" method="POST">
								<input class="input" type="text" name="new_email" placeholder="New Email">
								<br><br>
								<input class="button is-primary" type="submit" name="newEmailSubmit">
							</form>
							<br>
							<form action="profilePicture.php" method="POST" enctype="multipart/form-data">
								<input class="input" type="text" name="filename" placeholder="File name . . .">
								<input class="input" type="file" name="file">
								<button class="button" type="submit" name="submitProfile">Upload</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>';
			?>
		<?php
			$username = $_SESSION['id'];
			$sql = "SELECT * FROM profileimages WHERE profileUserId=? limit 1";
			$result = $conn->prepare($sql);
			$result->execute(array($username));
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
						<div class="columns body-columns">
							<div class="column is-half is-offset-one-quarter">
								<div class="card">
									<div class="card-image">
										<figure class="image is-1by1">
											<img src="./profile_images/'.$row["profilePath"].'">
										</figure>
									</div>
								<div class="level">
									<div class="level-left">
										<form class="form-control" method="POST" action="deleteProfilePicture.php">
											<input type="hidden" name="profilePath" value="'.$row["profilePath"].'">
											<button class="button is-hovered" type="submit" name="deleteProfileImage">
												<i class="material-icons">delete</i>
											</button>
										</form>
									</div>
								</div>
								</div>
							</div>
						</div>
						<div class="columns body-columns">
							<div class="column is-half is-offset-one-quarter"> <!-- place everything to the middle -->
								<footer class="footer">
									<div class="container is-fluid">
										<div class="content has-text-centered">
											<p>
												<strong>InstagramClone</strong> by ghorvath
											</p>
										</div>
									</div>
								</footer>
							</div>
						</div>';
				}
			}
		?>
</body>
</html>