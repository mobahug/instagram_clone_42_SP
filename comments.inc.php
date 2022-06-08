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
	if (isset($_POST['commentSubmit']))
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
		."INNER JOIN galleryimages ON user.id = galleryimages.userid "
		."WHERE galleryimages.idGallery =?";

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
		header("Location: homePage.php");

	}
}

/* getting from database the informations */

function getComments($conn, $imgid)
{
	//check out later
	$sql = "SELECT * FROM comments WHERE imgid=".$imgid." ORDER BY date DESC";
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
				<div class='columns body-columns'>
					<div class='column is-half is-offset-one-quarter'>
						<div class='card'>
							<div class='card-content'>
								<div class='content'
									<div class='is-text-overflow-parent'>
										<div class='is-text-overflow'>
											<a href='clicked-user-page.php?user=".$row2[0]['id']."'>
												<p class='title is-5'>".$row2[0]['uid']."</p>
											</a>
											<p class='subtitle is-6'>".$row['date']."</p>
											<p class='subtitle is-6'>".htmlspecialchars(nl2br($row['message']))."</p>
										</div>

									";
			if (isset($_SESSION['id']))
			{
				if ($_SESSION['id'] == $row2[0]['id'])
				{
					echo "<div class='level'>
							<div class='level-left'>
								<form class='form-control' method='POST' action='homePage.php?action=deleteComments'>
									<input type='hidden' name='cid' value='".$row['cid']."'>
									<button class='button is-hovered' type='submit' name='commentDelete'>Delete</button>
								</form>
							</div>
							<div class='level-right'>
								<form class='form-control' method='POST' action='editcomment.php'>
									<input type='hidden' name='cid' value='".$row['cid']."'>
									<input type='hidden' name='uid' value='".$row['uid']."'>
									<input type='hidden' name='date' value='".$row['date']."'>
									<input type='hidden' name='message' value='".$row['message']."'>
									<button class='button is-hovered'>Edit</button>
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
		}
		//checks for used email
		$sql2 = "SELECT * FROM `user` WHERE `email`=?";
		$result2 = $conn->prepare($sql2);
		$result2->execute(array($email));
		if ($result2 && $result2->fetchColumn())
		{
			header('Refresh: 0.1; ./index.php?user=emailexsist');
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
			}
			else if (!$lowercase)
			{
				header('Refresh: 0.1; ./index.php?user=lowercase');
			}
			else if (!$number)
			{
				header('Refresh: 0.1; ./index.php?user=number');
			}
			else if (!$specialChars)
			{
				header('Refresh: 0.1; ./index.php?user=special');
			}
			else if (strlen($password) < 8)
			{
				header('Refresh: 0.1; ./index.php?user=tooshort');
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