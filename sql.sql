CREATE TABLE comments (
	cid int(11) not null AUTO_INCREMENT PRIMARY KEY,
	uid varchar(128) not null,
	date datetime not null,
	message TEXT not null
);


CREATE TABLE IF NOT EXISTS user (
			id int(11) not null AUTO_INCREMENT PRIMARY KEY,
			uid varchar(128) not null,
			password varchar(512) not null,
			email varchar(128) not null,
			activation_code varchar(512) not null,
			status int(11) not null
		);


CREATE TABLE galleryImages (
	idGallery int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
	titleGallery LONGTEXT NOT NULL,
	descGallery LONGTEXT NOT NULL,
	imgFullNameGallery LONGTEXT NOT NULL,
	orderGallery LONGTEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS pwdReset (
	pwdResetId int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	pwdResetEmail TEXT NOT NULL,
	pwdResetSelector TEXT NOT NULL,
	pwdResetToken LONGTEXT NOT NULL,
	pwdResetExpires TEXT NOT NULL
);

INSERT INTO user (uid, password) VALUES ('admin', '123');
INSERT INTO user (uid, password) VALUES ('daniel', '123');

/* <a href="#">
	<div style="background-image: url(./user_uploads/'.$row["imgFullNameGallery"].');"></div>
	<h3>'.$row["titleGallery"].'</h3>
	<p>'.$row["descGallery"].'</p>
</a> */