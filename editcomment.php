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
	<link rel="stylesheet" href="style.css">
</head>
<body>
<div class='columns body-columns'>
	<div class='column'>
	<?php
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
								<textarea class='textarea is-large' placeholder='Edit your message' name='message'>".htmlspecialchars($message)."</textarea><br>
							</div>
						</div>
						<button class='button button-signin is-fullwidth' type='submit' name='commentSubmit'>Edit</button>
					</form>";
	?>
	</div>
</div>
</section>
</body>
</html>