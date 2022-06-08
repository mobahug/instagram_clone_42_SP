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
												echo "<p class='has-text-success has-background-success-light'>Check your email!</p>";
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