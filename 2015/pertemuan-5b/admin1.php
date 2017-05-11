<?php 
/**
 * admin1.php
 *
 * Sandhika Galih
 * sandhikagalih@unpas.ac.id
 *
 * Halaman home sederhana ketika berhasil login.
 * sekarang halaman ini punya akses ke $_SESSION
 * asalkan menggunakan fungsi session_start() di awal halaman
 * menambahkan link ke halaman logout1.php untuk menghapus session
 *
 */



session_start();

// cek apabila user mencoba mengakses langsung halaman ini
if (!isset($_SESSION["username"])) {
	header("Location: login1.php");
}

?>

<!doctype html>
<html>
<head>
	<title>Home</title>
</head>
<body>
	<h1>Home</h1>
	<h3>Selamat Datang <?= htmlspecialchars($_SESSION["username"]); ?>, Anda Telah Login.</h3>
	<p><a href="logout1.php">Logout</a></p>
</body>
</html>
