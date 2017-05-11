<?php 
// cek apabila user mengakses langsung halaman ini
if (!isset($_POST["username"]) && !isset($_POST["password"]) && !isset($_POST["email"]) && !isset($_POST["gender"]) && !isset($_POST["tanggal"]) && !isset($_POST["bulan"]) && !isset($_POST["tahun"]) && !isset($_POST["kota"]) && !isset($_POST["tentang_saya"])) {
	
	header("Location: http://localhost:8888/pw2/pertemuan-4b/latihan3.php");
	exit();
}

?>
<!doctype html>
<html>
<head>
	<title>Data Registrasi</title>
	<style>
		ul {margin: 0; padding: 0; list-style: none;}
	</style>
</head>
<body>

<h1>Terimakasih telah mendaftar</h1>
<h3>Berikut ini adalah data pendaftaran anda:</h3>
<ul>
	<li>Username : <?= $_POST["username"] ?></li>
	<li>Password : <?= $_POST["password"] ?></li>
	<li>Email : <?= $_POST["email"] ?></li>
	<li>Jenis Kelamin : <?= $_POST["gender"] ?></li>
	<li>Tanggal Lahir : <?= ($_POST["tanggal"] . " " . $_POST["bulan"] . " " . $_POST["tahun"]);  ?></li>
	<li>Tentang Saya : </li>
	<li><?= $_POST["tentang_saya"] ?></li>
</ul>

</body>
</html>