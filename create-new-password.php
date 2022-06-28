<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>mangofruit.fi</title>
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
							<br>
							<label class="label">New Password</label>
							<?php
								$selector = $_GET['selector'];
								$validator = $_GET['validator'];

								if (empty($selector) || empty($validator))
								{
									header("Location: index.php?nicetry");
								}
								else
								{
									if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false)
									{
										echo '
											<form action="reset-password.inc.php" method="post">
												<input type="hidden" name="selector" value="'.htmlspecialchars($selector).'">
												<input type="hidden" name="validator" value="'.htmlspecialchars($validator).'">
												<input class="input" type="password" name="pwd" placeholder="Enter a new password!">
												<br><br>
												<input class="input" type="password" name="pwd-repeat" placeholder="Enter again new password!">
												<br><br>
												<button class="button button-forget is-fullwidth" type="submit" name="reset-password-submit">Reset Password</button>
											</form>';
									}
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
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
</body>
</html>