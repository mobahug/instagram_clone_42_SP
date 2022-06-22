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
			$cid = $_POST['cid'];
			$uid = $_POST['uid'];
			$date = $_POST['date'];
			$message = $_POST['message'];

				echo	"<form class='box' method='POST' action='".editComments($conn)."'>
							<input type='hidden' name='cid' value='".htmlspecialchars($cid)."'>
							<input type='hidden' name='uid' value='".htmlspecialchars($uid)."'>
							<input type='hidden' name='date' value='".htmlspecialchars($date)."'>
							<div class='field'>
								<div class='control'>
									<textarea maxlength='1500' class='textarea is-large' placeholder='Edit your message' name='message'>".htmlspecialchars($message)."</textarea><br>
								</div>
							</div>
							<button class='button button-signin is-fullwidth' type='submit' name='commentSubmit'>Edit</button>
						</form>";
		}
		else
			header("Location: index.php?nicetry");
		?>
		</div>
	</div>
</section>
</body>
</html>