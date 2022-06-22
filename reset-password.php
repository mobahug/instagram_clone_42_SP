<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Instagram Clone</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
	<link rel="stylesheet" href="style.css">
	<title>Reset-Password</title>
</head>
<body>

	<section class="hero is-fullheight">
		<div class="hero-body">
			<div class="container">
				<div class="columns is-centered">
					<div class="column is-5-tablet is-4-desktop is-3-widescreen">
						<div class="box">
							<img class="image " src ="./resources/logo2.png" >

							<form action="reset-request.inc.php" method="post">
								<div class="field">
									<label class="label">Email</label>
									<?php
										if (isset($_GET['reset']))
										{
											if ($_GET['reset'] == "success")
											{
												echo "<p class='has-text-success has-background-success-light'>Check your email/spam folder!</p>";
											}
											if ($_GET['reset'] == "failed")
											{
												echo "<p class='has-text-danger has-background-danger-light'>Something went wrong!</p>";
											}
										}
										if (isset($_GET['newpwd']))
										{
											if ($_GET['newpwd'] == "empty")
											{
												echo "<p class='has-text-danger has-background-danger-light'>Password reset error!</p>";
											}
											if ($_GET['newpwd'] == "pwdnotsame")
											{
												echo "<p class='has-text-danger has-background-danger-light'>Password not match!</p>";
											}
											if ($_GET['newpwd'] == "uppercase")
											{
												echo "<p class='has-text-danger has-background-danger-light'>Password: Contains at least one uppercase letter!</p>";
											}
											if ($_GET['newpwd'] == "lowercase")
											{
												echo "<p class='has-text-danger has-background-danger-light'>Password: Contains at least one lowercase letter!</p>";
											}
											if ($_GET['newpwd'] == "number")
											{
												echo "<p class='has-text-danger has-background-danger-light'>Password: Need at least one number!</p>";
											}
											if ($_GET['newpwd'] == "special")
											{
												echo "<p class='has-text-danger has-background-danger-light'>Password: Need at least one special character!</p>";
											}
											if ($_GET['newpwd'] == "tooshort")
											{
												echo "<p class='has-text-danger has-background-danger-light'>Passwords min.: 8 characters long!</p>";
											}
											if ($_GET['newpwd'] == "toolong")
											{
												echo "<p class='has-text-danger has-background-danger-light'>Password is too long!</p>";
											}
											if ($_GET['newpwd'] == "success")
											{
												echo "<p class='has-text-success has-background-success-light'>Password sucessfully updated!</p>";
											}
										}
									?>
									<div class="control">
										<input class="input" type="email" name="email" value="" placeholder="example@email.com" />
									</div>
								</div>
								<div class="has-text-centered">
									<input class="button input button-signin" type="submit" name="reset-request-submit" />
								</div>
							</form>
							<br>
							<div class="has-text-centered">
								<button type="submit" class="button is-light is-fullwidth " name="loginSubmit">
									<a href="index.php" class="has-text-black">Back</a>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</body>
</html>