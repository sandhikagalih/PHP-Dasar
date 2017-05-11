<?php

define ("DAFTAR_USER", "daftar_user.php");

function tambah_daftar_keanggotaan($nama, $email) {
	$nama_baru = htmlspecialchars($nama);
	$email_baru = htmlspecialchars($email);
	file_put_contents(DAFTAR_USER, "$nama_baru : $email_baru\n", FILE_APPEND);
}

function ingat_input($input) {
	if ( !empty($_POST["$input"]) ) {
		return $_POST["$input"];
	}

	return "";
}

function valid_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}





function get_user_terdaftar($path = DAFTAR_USER) {
	$daftar_user = file($path);

	for( $i = 0; $i < count($daftar_user); $i++ ) {
		$users[$i] = explode(": ", $daftar_user[$i]);
	}
	
	return $users;

	
}
 
?>



















