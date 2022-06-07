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
	<title>Reset-Password</title>
</head>
<body>

	<section class="hero is-primary is-fullheight">
		<div class="hero-body">
			<div class="container">
				<div class="columns is-centered">
					<div class="column is-5-tablet is-4-desktop is-3-widescreen">
						<div class="box">
							<img class="image " src ="./resources/instagram-text-logo-83656.png" >
							<?php
								$selector = $_GET['selector'];
								$validator = $_GET['validator'];

								if (empty($selector) || empty($validator))
								{
									echo "Coul not validate your request";
								}
								else
								{
									if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false)
									{
										echo '
											<form action="reset-password.inc.php" method="post">
												<input type="hidden" name="selector" value="'.$selector.'">
												<input type="hidden" name="validator" value="'.$validator.'">
												<input class="input" type="password" name="pwd" placeholder="Enter a new password!">
												<input class="input" type="password" name="pwd-repeat" placeholder="Enter again new password!">

												<button class="button is-primary" type="submit" name="reset-password-submit">Reset Password</button>
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
</body>
</html>