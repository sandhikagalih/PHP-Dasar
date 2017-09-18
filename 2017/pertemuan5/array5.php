<?php 
$mahasiswa = [["sandhika.jpeg", "Sandhika Galih", "043040023", "sandhikagalih@gmail.com"]];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Daftar Mahasiswa</title>
</head>
<body>
	<h1>Daftar Mahasiswa</h1>
	<ul>
		<li><img src="img/<?php echo $mahasiswa[0]; ?>"></li>
		<li><?php echo $mahasiswa[1]; ?></li>
		<li><?php echo $mahasiswa[2]; ?></li>
		<li><?php echo $mahasiswa[3]; ?></li>
	</ul>








	
</body>
</html>