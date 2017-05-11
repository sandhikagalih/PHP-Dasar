<!doctype html>
<html>
<head>
	<title>Coba XML</title>
</head>
<body>

<h1>Daftar Mahasiswa</h1>

<?php 
	$xml = simplexml_load_file("buku.xml");

	foreach ($xml->mahasiswa as $mahasiswa ) : ?>
		<h3><?= $mahasiswa->nama; ?></h3>
		<ul>
			<li>NRP: <?= $mahasiswa["nrp"]; ?></li>
			<li>Fakultas: <?= $mahasiswa->fakultas; ?></li>
			<li>Jurusan: <?= $mahasiswa->jurusan; ?></li>
			<li>IPK: <?= $mahasiswa->ipk; ?></li>
			<li>Status: <?= $mahasiswa->status; ?></li>
		</ul>
	<?php endforeach; ?>

</body>
</html>