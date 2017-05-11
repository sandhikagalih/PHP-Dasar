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
			<input type="text" name="nama" id="nama" value="<?= htmlspecialchars($result["nama"]); ?>">
		</div>
		<div>
			<label for="universitas">Universitas</label>
			<input type="text" name="universitas" id="universitas" value="<?= htmlspecialchars($result["universitas"]); ?>">
		</div>
		<input type="hidden" name="id" value="<?= htmlspecialchars($result["id"]); ?>">
		<input type="submit" name="ubah" value="Ubah Data">
	</form>

</body>
</html>