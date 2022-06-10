<?php
	session_start();
	require_once 'database.php';

	try
	{
		$conn = new PDO($DB_HOST, $DB_USER, $DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "CREATE DATABASE IF NOT EXISTS commentsection";

		$conn->exec($sql);
	}

	catch(PDOException $e)
	{
		echo $sql . "<br>" . $e->getMessage();
	}
	//$conn = null;

	try
	{
		$conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql_comments = "CREATE TABLE IF NOT EXISTS comments (
			cid int(11) not null AUTO_INCREMENT PRIMARY KEY,
			uid varchar(128) not null,
			imgid int(11) not null,
			date datetime not null,
			message TEXT not null
		);";

		$conn->exec($sql_comments);

		$sql_users = "CREATE TABLE IF NOT EXISTS user (
			id int(11) not null AUTO_INCREMENT PRIMARY KEY,
			uid varchar(128) not null,
			password varchar(512) not null,
			email varchar(128) not null,
			activation_code varchar(512) not null,
			status int(11) not null,
			notification_status int(11) not null,
			profilePicture varchar(128) not null
		);";

		$conn->exec($sql_users);

		$sql_gallery = "CREATE TABLE IF NOT EXISTS galleryImages (
			idGallery int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
			userid int(11) NOT NULL,
			titleGallery LONGTEXT NOT NULL,
			descGallery LONGTEXT NOT NULL,
			imgFullNameGallery LONGTEXT NOT NULL,
			orderGallery LONGTEXT NOT NULL,
			upload_date datetime NOT NULL
		);";

		$conn->exec($sql_gallery);

		$sql_forget_pwd = "CREATE TABLE IF NOT EXISTS pwdReset (
			pwdResetId int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
			pwdResetEmail TEXT NOT NULL,
			pwdResetSelector TEXT NOT NULL,
			pwdResetToken LONGTEXT NOT NULL,
			pwdResetExpires TEXT NOT NULL
		);";

		$conn->exec($sql_forget_pwd);

		$sql_like = "CREATE TABLE IF NOT EXISTS `like` (
			id int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
			user int(11) NOT NULL,
			img TEXT NOT NULL
		);";

		$conn->exec($sql_forget_pwd);
	}

	catch(PDOException $e)
	{
		echo $sql . "<br>" . $e->getMessage();
	}
	//$conn = null;
?>