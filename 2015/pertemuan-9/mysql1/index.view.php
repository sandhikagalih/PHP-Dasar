<!doctype html>
<html>
<head>
	<title>Daftar Teman</title>
</head>
<body>

<h1>Daftar mahasiswa</h1>

<?php 
	$result = query($conn, "SELECT * FROM mahasiswa");
	
	foreach ( $result as $row ) : ?>
	
	
	<ul>
		<li><strong><?= $row["nama"]; ?></strong></li>
		<li><?= $row["universitas"]; ?></li>
	</ul>
	
	<?php endforeach; ?>

</body>
</html>