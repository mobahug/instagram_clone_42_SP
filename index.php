<?php
	require_once 'comments.inc.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Instagram Clone</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
		<link rel="stylesheet" href="style.css">
	</head>
	<body >
<!--
		Login Section
						-->
	<div class="navbar is-inline-flex is-transparent">
		<div class="navbar-brand">
			<a class="navbar-item" href="homePage.php">
				<img src="./resources/logo2.png" width="85" height="28" alt="InstagramClone">
			</a>
		</div>
		<div class="navbar-menu"></div>
		<div class="navbar-item is-flex-touch">
			<?php
				if (isset($_SESSION['id']))
				{
					echo '
						<a class="navbar-item" href="index.php?action=logout">
							<i class="material-icons">logout</i>
						</a>
						<a class="navbar-item">
							<i class="material-icons">favorite_border</i>
						</a>
						<a class="navbar-item" href="profilePage.php">
							<i class="material-icons">person_outline</i>
						</a>
					';
				}
			?>
		</div>
	</div>
		<section class="hero is-fullheight">
			<div class="hero-body">
				<div class="container">
					<div class="columns is-centered">
						<div class="column is-5-tablet is-4-desktop is-3-widescreen">
								<!--
										FORM
												-->
							<img src ="./resources/logo2.png" >
							<div class="box">
								<?php
									if (isset($_GET['loginfailed']))
									{
										echo "<p class='has-text-danger has-background-danger-light'>Wrong username/password!</p>";
									}
									if (isset($_GET['user']))
									{
										if ($_GET['user'] == "exsist")
										{
											echo "<p class='has-text-danger has-background-danger-light'>Username already in use!</p>";
										}
										if ($_GET['user'] == "emailexsist")
										{
											echo "<p class='has-text-danger has-background-danger-light'>Email already in use!</p>";
										}
										if ($_GET['user'] == "uppercase")
										{
											echo "<p class='has-text-danger has-background-danger-light'>Contains at least one uppercase letter!</p>";
										}
										if ($_GET['user'] == "lowercase")
										{
											echo "<p class='has-text-danger has-background-danger-light'>Contains at least one lowercase letter!</p>";
										}
										if ($_GET['user'] == "number")
										{
											echo "<p class='has-text-danger has-background-danger-light'>Need at least one number!</p>";
										}
										if ($_GET['user'] == "special")
										{
											echo "<p class='has-text-danger has-background-danger-light'>Need at least one special character!</p>";
										}
										if ($_GET['user'] == "tooshort")
										{
											echo "<p class='has-text-danger has-background-danger-light'>Passwords min.: 8 characters long!</p>";
										}
										if ($_GET['user'] == "too_long_username")
										{
											echo "<p class='has-text-danger has-background-danger-light'>User name is too long!</p>";
										}
									}
									if (isset($_GET['registration']))
									{
										if ($_GET['registration'] == "success")
										{
											echo "<p class='has-text-success has-background-success-light'>Success! Chek your email!</p>";
										}
										if ($_GET['registration'] == "failed")
										{
											echo "<p class='has-text-danger has-background-danger-light'>Something went wrong!</p>";
										}
									}
									if (isset($_GET['newpwd']))
									{
										if ($_GET['newpwd'] == "passwordupdated")
										{
											echo "<p class='has-text-success has-background-success-light'>Password has been reseted</p>";
										}
									}
								?>
								<form action="index.php?action=login" method="post">
									<div class="field">
										<label class="label">Username</label>
										<div class="control">
											<input class="input" type="text" name="uid" placeholder="Username" value="" required>
										</div>
									</div>
									<div class="field">
										<label class="label">Password</label>
										<div class="control">
											<input class="input" type="password" name="password" placeholder="Password" value="" required>
										</div>
									</div>
									<br>
									<div class="control">
										<div class="columns is-centered">
											<button type="submit" class="button is-light is-fullwidth" name="loginSubmit">Log In</button>
										</div>
									</div>
								</form>
								<br>
								<br>
								<div class="control">
									<div class="columns is-centered">
										<button type="submit" class="button is-fullwidth button-signin" id='btn' name="submit" value="OK">Sign Up</button>
									</div>
								</div>
								<br>
								<br>
								<div class="control">
									<div class="columns is-centered">
										<a class="button button-forget is-fullwidth" href="reset-password.php">
											<p>Forget your password?</p>
										</a>
									</div>
								</div>
							</div>
						</div>
						<!--
							Popup registration window
						-->
						<div>
							<div class="modal">
								<div class="modal-background"></div>
								<div class="is-clipped modal-content center">
									<div class="columns body-columns">
										<div class="column is-half is-offset-one-quarter">
											<div class='box'>
												<img class="image " src ="./resources/logo2.png" >
												<form action="index.php?action=register" method="post">
													<div class="field">
														<label class="label">Email</label>
														<div class="control">
															<input class="input" type="email" name="email" value="" placeholder="example@email.com" />
														</div>
													</div>

													<div class="field">
														<label class="label">Username</label>
														<div class="control">
															<input class="input" type="text" name="username" value="" placeholder="Example123"/>
														</div>
													</div>

													<div class="field">
														<label class="label">Password</label>
														<div class="control">
															<input class="input" type="password" name="password" value="" placeholder="Password"/>
														</div>
													</div>

													<div class=" has-text-centered">
														<input class="button input button-signin" type="submit" name="submit" value="OK" />
													</div>
												</form>
											</div>
										</div>
										<button class="is-overlay modal-close is-large" aria-label="close">Model</button>
									</div>
								</div>
								<!--
										Close button
												-->
								<script>
									// Bulma does not have JavaScript included,
									// hence custom JavaScript has to be
									// written to open or close the modal
									const modal = document.querySelector('.modal');
									const btn = document.querySelector('#btn')
									const close = document.querySelector('.modal-close')

									btn.addEventListener('click', function () {
										modal.style.display = 'block'
									})

									close.addEventListener('click', function () {
										modal.style.display = 'none'
									})

									window.addEventListener('click', function (event) {
										if (event.target.className === 'modal-background') {
											modal.style.display = 'none'
										}
									})
								</script>
							</div>
						</div>
					</div>
									<!-- pagination div -->
					<div class="">
						<?php
							$sql = "SELECT * FROM `galleryImages`
									INNER JOIN user ON galleryImages.userid = user.id
									ORDER BY `upload_date` DESC";
							$result = $conn->prepare($sql);
							$result->execute();
							$img_counter = 1;
							if (!$result)
							{
								echo "SQL statement failed!";
							}
							else
							{
								$block = 1;
								while ($row = $result->fetch())
								{
									/* display pagination first page always */
									if ($img_counter == 1 && $block == 1)
									{
										echo '<div style="display: block;" id="page'.htmlspecialchars($block).'">
												<div class="columns is-multiline is-centered">';
									}
									/* trigger pagination other pages */
									else if ($img_counter == 1 && $block > 1)
									{
										echo '<div style="display: none;" id="page'.htmlspecialchars($block).'">
												<div class="columns is-multiline is-centered">';
									}
									echo	'
											<div class="column is-one-quarter-desktop is-half-tablet">
												<div class="card">
													<div class="card-image">
														<div class="box">
															<figure class="image is-128x90">
																<img id="img'.htmlspecialchars($img_counter).'" src="./user_uploads/'.htmlspecialchars($row["imgFullNameGallery"]).'" alt="'.htmlspecialchars($row['titleGallery']).'">
															</figure>
														</div>
													</div>
												</div>
											</div>';
									$img_counter++;
									if ($img_counter > 6)
									{
										echo '
											</div>
										</div>';
										/* break ; */
										$img_counter = 1;
										$block++;
									}
								}
								/* fulfill with none images if there is not fulfilled the block */
								if ($img_counter != 1 && $img_counter < 7)
								{
									while ($img_counter < 7)
									{
										if ($img_counter % 2 == 0)
											echo "<img class='img".htmlspecialchars($img_counter)."' src='./resources/logo2.png' style='display: none;'>";
										else
											echo "<img class='img".htmlspecialchars($img_counter)."' src='./resources/logo2.png' style='display: none;'>";
										$img_counter++;
									}
									echo "</div>";
								}
							}
						?>
					</div>
					<div>
						<?php
							for ($k = 1; $k < ($block + 1); $k++)
							{

								if ($k == 1)
								{
									echo '
									<div>
										<a id="button'.$k.'" class="pagination-link is-current" onclick="showPages('.$k.', '.$block.')">'.$k.'</a>';
								}
								else
								{
									echo '
										<a id="button'.$k.'" class="pagination-link href="#" onclick="showPages('.$k.', '.$block.')">'.$k.'</a>
									</div>';
								}
							}
						?>
					</div>
					</div>
				</div>
			</div>
		</section>
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
	<script>
		function showPages(id, numberOfPages)
		{

			for(let i=1; i<=numberOfPages; i++)
			{

				if (document.getElementById('page'+i))
				{
					document.getElementById('page'+i).style.display='none';
					document.getElementById('button'+i).classList.remove("is-current");
				}
			}
			if (document.getElementById('page'+id))
			{
				let block = document.getElementById('page'+id);
				let activeLink = document.getElementById('button'+id);
				block.style.display='block';
				activeLink.classList.add("is-current");
			}
		}
	</script>
	</body>
</html>
