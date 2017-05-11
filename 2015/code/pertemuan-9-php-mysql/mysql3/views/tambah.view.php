<!doctype html>
<html>
<head>
	<title>Tambah Data Mahasiswa</title>
</head>
<body>

	<div><p><?= ( isset($pesan) ) ? $pesan : ""; ?></p></div>

	<form action="" method="post">
		<div>
			<label for="nama">Nama</label>
			<input type="text" name="nama" id="nama">
		</div>
		<div>
			<label for="universitas">Universitas</label>
			<input type="text" name="universitas" id="universitas">
		</div>
		<input type="submit" name="tambah" value="Tambah Data">
	</form>

</body>
</html>