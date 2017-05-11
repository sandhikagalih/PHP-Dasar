<?php
session_start();
require '../helpers/functions.php';

// koneksi ke database dan memilih database
$conn = koneksi($config);

// data user ini hanya untuk demo, pada kenyataannya nanti menggunakan database
define("USERNAME", "admin");
define("PASSWORD", "motekar");



// cek apabila tombol submit sudah di-klik / form telah di-submit
if (isset($_POST["submit"])) {
    // simpan data $_POST ke dalam variabel
    $username = $_POST["username"];
    $password = $_POST["password"];

    $users = query($conn, "SELECT * FROM user");

    foreach ($users as $user) {

	    // validasi login user
	    if ($username == $user["username"] && $password == $user["password"]) {
	      // login berhasil

	      // set session user, variabel yang dapat digunakan di semua halaman
	      $_SESSION["username"] = $username;

	      // redirect user ke halaman admin
	      header("Location: main.php");
	      exit;
	  
	    } else {
	      // login gagal
	      // apabila username & password kosong atau tidak sesuai
	      $error = true;
	    }

	}
}


require '../views/login.view.php';

?>