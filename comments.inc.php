<?php

require_once 'config/setup.php';
require_once 'notification.php';

date_default_timezone_set('Europe/Helsinki');


//$conn = mysqli_connect('localhost', 'root', 'debian', 'commentsection');

//if (!$conn)
//{
//	die("Connection failed: ".mysqli_connect_error());
//}

/* storing comments username and time into sql database */

function setComments($conn)
{
	if (isset($_POST['commentSubmit']) && !empty($_POST['message']))
	{
		$uid = $_POST['uid'];
		$date = $_POST['date'];
		$message = $_POST['message'];
		$imgid = $_POST['imgid'];


		$headers = 'From: gabocza12@gmail.com' . "\r\n" .
					'Reply-To: gabocza12@gmail.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

		$sql = "INSERT INTO comments (uid, imgid, date, message) VALUES (?, ?, ?, ?)";

		$result = $conn->prepare($sql);
		$result->execute(array($uid, $imgid, $date, $message));



		//$sql2 = "SELECT * FROM `user` WHERE `id`='$uid'";
		$sql2 = "SELECT user.id AS userid, user.email AS email, notification_status FROM user "
		."INNER JOIN galleryImages ON user.id = galleryImages.userid "
		."WHERE galleryImages.idGallery =?";

		$result = $conn->prepare($sql2);
		$result->execute(array($imgid));
		if (!$result)
		{
			echo "SQL statement failed!";
		}
		else
		{
			$row = $result->fetchAll();
			if ($row[0]['notification_status'] == 1)
			{
				mail($row[0]['email'], "Comment Notification", "Somebody commented to your picture " . PHP_EOL . $headers);
			}
		}
		//header("Location: homePage.php");
	}
	else
		header("Location: homePage.php?comment=error_field");
}

/* getting from database the informations */

function getComments($conn, $imgid)
{
	//check out later
	$sql = "SELECT * FROM comments WHERE imgid=".$imgid." ORDER BY date ASC";
	$result = $conn->query($sql);

	/* write out all the earlier comments with text username and date */
	while ($row = $result->fetch())
	{
		/* if you logged in add to your comment your name
			and can delete or edit only the logged in user its own comment*/
		$id = $row['uid'];
		$sql2 = "SELECT * FROM `user` WHERE BINARY id=?";
		$result2 = $conn->prepare($sql2);
		$result2->execute(array($id));
		$row2 = $result2->fetchAll();
		//print_r($row2);
		if (is_array($row2) && count($row2) > 0)
		{
			//header('Content-type: text/plain');
			echo "
				<div id='txtHint'class='columns body-columns'>
					<div class='column is-half is-offset-one-quarter'>
						<div class='card'>
							<div class='card-content'>
								<div class='is-text-overflow-parent'>
									<div class='is-text-overflow'>
										<div class='header'>
											<div class='media'>
												<div class='media-left'>
													<figure class='pt-2 image is-32x32'>
														<img class='is-rounded image is-32x32' src='./profile_images/".htmlspecialchars($row2[0]["profilePicture"])."' alt='Placeholder image'>
													</figure>
												</div>
												<div class='media-content'>
													<a href='clicked-user-page.php?user=".htmlspecialchars($row2[0]['id'])."'>
														<p class='title is-5'>".htmlspecialchars($row2[0]['uid'])."</p>
													</a>
													<p class='subtitle is-6'>".htmlspecialchars($row['date'])."</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							
								<div class='mt-4 content'>
									<p class='subtitle is-6'>".htmlspecialchars(nl2br($row['message']))."</p>
								</div>";
			if (isset($_SESSION['id']))
			{
				if ($_SESSION['id'] == $row2[0]['id'])
				{
					echo "
					<div class='card-footer'>
						<div class='container'>
							<form class='form-control' method='POST' action='homePage.php?action=deleteComments'>
								<input type='hidden' name='cid' value='".htmlspecialchars($row['cid'])."'>
								<div class='field'>
									<button class='is-pulled-left button is-hovered' type='submit' name='commentDelete'>Delete</button>
								</div>
							</form>
							<form class='form-control' method='POST' action='editcomment.php'>
								<input type='hidden' name='cid' value='".htmlspecialchars($row['cid'])."'>
								<input type='hidden' name='uid' value='".htmlspecialchars($row['uid'])."'>
								<input type='hidden' name='date' value='".htmlspecialchars($row['date'])."'>
								<input type='hidden' name='message' value='".htmlspecialchars($row['message'])."'>
								<div class='field'>
									<button class='is-pulled-right button is-hovered'>Edit</button>
								</div>
							</form>
						</div>
					</div>";
				}
				/*else
				{
					echo "<form class='edit-form' method='POST' action='".replyComments($conn)."'>
						<input type='hidden' name='cid' value='".$row['cid']."'>
						<button type='submit' name='replySubmit'>Reply</button>
					</form>";
				}*/
			}
			else
			{
				echo "<p class='message is-danger'>You need to be logged in to reply!</p>";
			}

			echo "			
							</div>
						</div>
					</div>
				</div>";
		}
		/*else
		{
			echo "<form class='edit-form' method='POST' action='replycomment.php'>
						<input type='hidden' name='cid' value='".$row['cid']."'>
						<input type='hidden' name='uid' value='".$row['uid']."'>
						<input type='hidden' name='date' value='".$row['date']."'>
						<input type='hidden' name='message' value='".$row['message']."'>
						<button>Reply</button>
					</form>";
		}*/
	}
}

function editComments($conn)
{
	if (isset($_POST['commentSubmit']))
	{
		$cid = $_POST['cid'];
		$uid = $_POST['uid'];
		$date = $_POST['date'];
		$message = $_POST['message'];

		$sql = "UPDATE `comments` SET message=? WHERE cid=?";
		$result = $conn->prepare($sql);
		$result->execute(array($message, $cid));
		header("Location: homePage.php");
	}
}

function deleteComments($conn)
{
	if (isset($_POST['commentDelete']))
	{
		$cid = $_POST['cid'];

		$sql = "DELETE FROM `comments` WHERE cid=?";
		$result = $conn->prepare($sql);
		$result->execute(array($cid));
		//header("Location: index.php");
	}
}

/*function replyComments($conn)
{
	if (isset($_POST['replySubmit']))
	{
		$cid = $_POST['cid'];
		$uid = $_POST['uid'];
		$date = $_POST['date'];
		$message = $_POST['message'];
		$sql = "INSERT INTO comments (uid, date, message) VALUES ('$uid', '$date', '$message')";
		//$sql = "UPDATE comments SET message='$message' WHERE cid='$cid'";
		$result = $conn->query($sql);
		header("Location: index.php");
	}
}*/


function register($conn)
{
	/*all data have to be collected otherwise could break*/
	if ($_POST['email'] && $_POST['username'] && $_POST['password'] && $_POST['submit'] && $_POST['submit'] == "OK")
	{
		$email = $_POST['email'];
		$uid = strip_tags($_POST['username']);
		$status = 0;
		$activationCode = md5($email.time());
		$notification_status = 1;
		$defaultImage = "/128x128.png";

		$headers = 'From: gabocza12@gmail.com' . "\r\n" .
					'Reply-To: gabocza12@gmail.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

		//checks for existing users
		if (strlen($uid) > 16)
		{
			header('Refresh: 0.1; ./index.php?user=too_long_username');
			return false;
		}
		$sql = "SELECT * FROM `user` WHERE `uid`=?";
		$result = $conn->prepare($sql);
		$result->execute(array($uid));
		if ($result && $result->fetchColumn())
		{
			header('Refresh: 0.1; ./index.php?user=exsist');
			return false;
		}
		//checks for used email
		$sql2 = "SELECT * FROM `user` WHERE `email`=?";
		$result2 = $conn->prepare($sql2);
		$result2->execute(array($email));
		if ($result2 && $result2->fetchColumn())
		{
			header('Refresh: 0.1; ./index.php?user=emailexsist');
			return false;
		}
		else
		{
			/*creating password*/
			$password = $_POST['password'];

			$uppercase = preg_match('/[A-Z]/', $password);
			$lowercase = preg_match('/[a-z]/', $password);
			$number = preg_match('/[0-9]/', $password);
			$specialChars = preg_match('/[^a-zA-Z\d]/', $password);

			if (!$uppercase)
			{
				header('Refresh: 0.1; ./index.php?user=uppercase');
				return false;
			}
			else if (!$lowercase)
			{
				header('Refresh: 0.1; ./index.php?user=lowercase');
				return false;
			}
			else if (!$number)
			{
				header('Refresh: 0.1; ./index.php?user=number');
				return false;
			}
			else if (!$specialChars)
			{
				header('Refresh: 0.1; ./index.php?user=special');
				return false;
			}
			else if (strlen($password) < 8)
			{
				header('Refresh: 0.1; ./index.php?user=tooshort');
				return false;
			}
			else
			{
				$password = hash('whirlpool', $_POST['password']);
				$sql = "INSERT INTO `user` (`uid`, `password`, `email`, `activation_code`, `status`, `notification_status`, `profilePicture`) VALUES (?, ?, ?, ?, ?, ?, ?)";
				$result = $conn->prepare($sql);
				$result->execute(array($uid, $password, $email, $activationCode, $status, $notification_status, $defaultImage));
				header('Refresh: 0.1; ./index.php?registration=success');
				mail($email, "E-mail Verification", "Please verify your account " . PHP_EOL .
				"http://localhost:8080/IIII/activation_code.php?code=$activationCode", $headers);
			}
		}
	}
	else
		header('Refresh: 0.1; ./index.php?registration=failed');
}

function getLogin($conn)
{
	if (isset($_POST['loginSubmit']))
	{
		$uid = $_POST['uid'];

		$password = hash('whirlpool', $_POST['password']);

		$sql = "SELECT * FROM `user` WHERE `uid`=? AND `password`=? AND `status`=1";
		$result = $conn->prepare($sql);
		$result->execute(array($uid, $password));
		$row = $result->fetchAll();
		if (is_array($row) && count($row) == 1)
		{
			$_SESSION['id'] = $row[0]['id'];
			header("Location: profilePage.php");
			exit(); //exit php and revent to resubmit the new comment
		}
		else
		{
			header("Location: index.php?loginfailed");
			exit();
		}
	}
}

function userLogout()
{
	//session_start();
	session_destroy();
	header("Location: index.php");
	exit();
}

function deleteAccount($conn)
{
	$sql = "DELETE FROM `user` WHERE `id`=?";
	$result = $conn->prepare($sql);
	$result->execute(array($_SESSION['id']));
	session_destroy();
	header('Location: index.php');
}

if (isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case 'delete':
			deleteAccount($conn);
			break;
		case 'register':
			register($conn);
			break;
		case 'login':
			getLogin($conn);
			break;
		case 'logout':
			userLogout();
			break;
		case 'setComments':
			setComments($conn);
			break;
	/* 	case 'getComments':
			getComments($conn, $imgid);
			break; */
		case 'editComments':
			editComments($conn);
			break;
		case 'deleteComments':
			deleteComments($conn);
			break;
	}
}
?>