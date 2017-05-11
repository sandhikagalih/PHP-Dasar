<!doctype html>
<html>
<head>
	<title>User Terdaftar</title>
</head>
<body>

<h1>User yang sudah terdaftar</h1>
<ul>
<?php 
	if ( $user_terdaftar ) {
		foreach($user_terdaftar as $user) {
			list($nama, $email) = $user;
			echo "<li>$nama: <a href=\"mailto:$email\">$email</a></li>";
		}
	} else {
		echo "<li>Belum ada user yang mendaftar.</li>";
	}
?>
</ul>

</body>
</html>