<?php
	require 'comments.inc.php';
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
	<link href="style.css" rel="stylesheet">
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
				<a class="navbar-item" href="settings.php">
					<i class="material-icons has-text-success">settings</i>
				</a>
			</div>';
		}
		else
		{
			echo '
			<div class="navbar-item is-flex-touch">
				<a class="navbar-item" href="index.php?action=login">
					<i class="material-icons">login</i>
				</a>
			</div>';
		}
		?>
	</div>
	<div class="is-fullheight">
		<div class="container">
			<div class="columns body-columns">
				<div class="column is-half is-offset-one-quarter"> <!-- place everything to the middle -->
					<div class="container is-fluid">
						<?php
							echo '
								<div class="box has-text-centered">
									<form class="form-control" method="POST" action="settings.php?action=delete">';
							if (isset($_SESSION['id']))
							{
								$userid = $_SESSION['id'];
								$sql = "SELECT * FROM `user` WHERE `id`=?";
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
										echo '<input type="hidden" name="profilePath" value="'.htmlspecialchars($row["profilePicture"]).'">';
									}
								}
								echo '
														<button class="button button-forget is-fullwidth" type="submit" name="deleteAllimg">
													<p class="has-text-black">Delete Account</p>
												</button>
											</form>
											<br>
											<div class="field">
												<form action="notification.php" method="POST">
													<input class="button is-light" type="submit" name="on" value="On">
													<input class="button is-light" type="submit" name="off" value="Off">
												</form>';
								$id = $_SESSION['id'];
								$sql2 = "SELECT * FROM `user` WHERE `id`=?";
								$result = $conn->prepare($sql2);
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

										if ($row['notification_status'] == 1)
										{
											echo "<p class='has-text-success has-background-success-light'>Email notification is ON!</p>";
										}
										if ($row['notification_status'] == 0)
										{
											echo "<p class='has-text-danger has-background-danger-light'>Email notification is OFF</p>";
										}

										echo '
										<br>
										<div>
											<label class="label">Change username</label>';
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
												if ($_GET['username'] == "toolong_username")
												{
													echo "<p class='has-text-danger has-background-danger-light'>Max 15 character long username!</p>";
												}
												if ($_GET['username'] == "tooless")
												{
													echo "<p class='has-text-danger has-background-danger-light'>Min 1 character username!</p>";
												}
												if ($_GET['username'] == "wrong_user")
												{
													echo "<p class='has-text-danger has-background-danger-light'>Wrong user!</p>";
												}
											}

										echo '
											<form action="new-username.php" method="POST">
												<input class="input" type="text" name="new_user" placeholder="New Username">
												<br>
												<input class="button button-signin is-fullwidth" type="submit" name="newUserSubmit">
											</form>
										</div>';

										echo '
										<br>
										<div>
											<label class="label">Change password</label>';

											if (isset($_GET['password']))
											{
												if ($_GET['password'] == "uppercase")
												{
													echo "<p class='has-text-danger has-background-danger-light'>Password: Contains at least one uppercase letter!</p>";
												}
												if ($_GET['password'] == "lowercase")
												{
													echo "<p class='has-text-danger has-background-danger-light'>Password: Contains at least one lowercase letter!</p>";
												}
												if ($_GET['password'] == "number")
												{
													echo "<p class='has-text-danger has-background-danger-light'>Password: Need at least one number!</p>";
												}
												if ($_GET['password'] == "special")
												{
													echo "<p class='has-text-danger has-background-danger-light'>Password: Need at least one special character!</p>";
												}
												if ($_GET['password'] == "tooshort")
												{
													echo "<p class='has-text-danger has-background-danger-light'>Passwords min.: 8 characters long!</p>";
												}
												if ($_GET['password'] == "toolong")
												{
													echo "<p class='has-text-danger has-background-danger-light'>Password is too long!</p>";
												}
												if ($_GET['password'] == "success")
												{
													echo "<p class='has-text-success has-background-success-light'>Password sucessfully updated!</p>";
												}
											}

										echo '
											<form action="change-password.php" method="POST">
												<input class="input" type="password" name="change-password" placeholder="New password ">
												<br>
												<input class="button button-signin is-fullwidth" type="submit" name="changePasswordSubmit">
											</form>
										</div>';

										echo '
										<br>
										<div>
											<label class="label">Change email</label>';
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
												if ($_GET['email'] == "toolong_format")
												{
													echo "<p class='has-text-danger has-background-danger-light'>Max 42 character long email!</p>";
												}
												if ($_GET['email'] == "tooshort_format")
												{
													echo "<p class='has-text-danger has-background-danger-light'>Too short email adress!</p>";
												}
												if ($_GET['email'] == "wrong_email")
												{
													echo "<p class='has-text-danger has-background-danger-light'>Wrong email!</p>";
												}
											}
										echo '
											<form action="new-email.php" method="POST">
												<input class="input" type="email" name="new_email" placeholder="New Email">
												<br>
												<input class="button button-signin is-fullwidth" type="submit" name="newEmailSubmit">
											</form>
											<br>';
											if (isset($_GET['user']))
											{
												if ($_GET['user'] == "new_profilepicture_uploaded")
												{
													echo "<p class='has-text-success has-background-success-light'>New profile picture sucessfully updated!</p>";
												}
												if ($_GET['user'] == "errorsize")
												{
													echo "<p class='has-text-danger has-background-danger-light'>File size is too big!</p>";
												}
												if ($_GET['user'] == "perror")
												{
													echo "<p class='has-text-danger has-background-danger-light'>You had an error!</p>";
												}
												if ($_GET['user'] == "notproperfile")
												{
													echo "<p class='has-text-danger has-background-danger-light'>You need to uppload a proper filetype!</p>";
												}
											}
										echo '<label class="label">Upload new profile picture (max.: 2mb)</label>
											<form action="profilePicture.php" method="POST" enctype="multipart/form-data">
												<input  maxlength="50" class="input" type="text" name="filename" placeholder="Profile Picture Name (Optional), max.: 50 character">
												<input class="input" type="file" name="file">
												<button class="button button-signin is-fullwidth" type="submit" name="submitProfile">Upload</button>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
						<div class="columns body-columns">
							<div class="column is-half is-offset-one-quarter">
								<div class="card">
									<div class="card-image">
										<figure class="image is-1by1">
											<img src="profile_images/'.htmlspecialchars($row["profilePicture"]).'">
										</figure>
									</div>
								<div class="level">
									<div class="level-left">
										<form class="form-control" method="POST" action="deleteProfilePicture.php">
											<input type="hidden" name="profilePath" value="'.htmlspecialchars($row["profilePicture"]).'">
											<button class="button is-hovered" type="submit" name="deleteProfileImage">
												<i class="material-icons">delete</i>
											</button>
										</form>
									</div>
								</div>
								</div>
							</div>
						</div>
						<div class="is-fullheight">
							<div class="container">
								<div class="columns body-columns">
									<div class="column is-half is-offset-one-quarter">
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
						</div>';
									}
								}
							}
							else
								header("Location: index.php?nicetry");
				?>
			</div>
		</div>
	</div>
</body>
</html>