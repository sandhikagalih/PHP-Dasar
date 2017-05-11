<?php 

require "functions.php";

if ( isset($_POST["submit"]) ) {
	$nama  = trim($_POST["nama"]);
	$email = trim($_POST["email"]);

	if ( empty($nama) || empty($email) ) {
		$status = "Nama dan Email tidak boleh kosong!!";
	} elseif  ( !valid_email($email) ) {
		$status = "Email tidak valid";
	} else {
		tambah_daftar_keanggotaan($nama, $email);
		$status = "Terima kasih telah mendaftar, data anda telah kami simpan!";
	}
}

require "index.view.php" 
?>