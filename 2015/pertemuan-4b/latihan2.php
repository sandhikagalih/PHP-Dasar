<?php 
 
$nama = $_GET["nama"];

?>
<!doctype html>
<html>
<head>
	<title>Friend List</title>
	<style>
		body { font-family: "lucida grande", tahoma, sans-serif; }
		ul, a, img, li { 
			margin: 0; 
			padding: 0; 
		}

		div {
			width: 250px;
			margin: 0 0 13px 13px;
			border: 1px solid #e9eaed;
			display: inline-block;
			height: 100px;
			position: relative;
			background-color: #f6f7f8;
		} 

		span.nama {
			position: absolute;
			left: 110px;
			top: 20px;
			color: #3b5998;
			font-size: 13px;
			font-weight: bold;
			text-decoration: none;
		}

		span.teman {
			position: absolute;
			color: #89919c;
			font-size: 10px;
			top: 38px;
			left: 110px;
		}

		h2 { margin-left: 12px; }
	</style>
</head>
<body>

<h2>Halo, Selamat Datang <?= $nama; ?></h2>
<div>
	<img src="images/<?=$nama; ?>.jpg" alt="<?=$nama; ?>">
	<span class="nama"><?=$nama; ?></span>
</div>

</body>
</html>