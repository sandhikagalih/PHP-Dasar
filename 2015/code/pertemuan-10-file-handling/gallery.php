<?php 
require 'functions.php'; 
$conn = konek();

$result = mysqli_query($conn, "SELECT * FROM gallery");

$rows = array();
while( $row = mysqli_fetch_assoc($result) ) {
	$rows[] = $row;
}

?>
<!doctype html>
<html>
<head>
	<title>Gallery</title>
	<style>
	body {
		width: 600px;
		margin: auto;
	}

	.image {
		float: left;
		margin-right:20px;
		margin-top:20px;
		width: 160px;
		height: 160px;
		background-size: 200px;
		background-position: center center;
		background-repeat: no-repeat;
		box-shadow: 0 0 0 5px white,
					0 0 0 6px darkblue;
		position: relative;
		cursor: pointer;
		overflow: hidden;
	}

	.image span {
		width: 100%;
		height: 30px;
		background-color: rgba(0,0,0,.5);
		position: absolute;
		bottom: -30px;
		text-align: center;
		line-height: 30px;
		color: #ddd;
		font-family: arial;
		font-size: .8em;

		-webkit-transition: all .3s;
		-moz-transition: all .3s;
		-o-transition: all .3s;
		-ms-transition: all .3s;
		transition: all .3s;
	}

	.image:hover span {
		bottom: 0;
	}

	.close {
		width: 14px;
		height: 14px;
		font-size: 20px;
		font-weight: bold;
		line-height: 14px;
		text-align: center;
		color: darkred;
		text-shadow: 0 1px 0 #ffffff;
		opacity: 0;
		filter: alpha(opacity=0);
		text-decoration: none;
		position: absolute;
		right: 0;
		background: white;
		padding: 5px;
		border-radius: 150px;
		border-radius: 2px solid darkred;
	}
	
	.image:hover .close {
		opacity: 0.6;
		filter: alpha(opacity=60);
	}

	.image:hover .close:hover {
		opacity: 0.8;
		filter: alpha(opacity=80);
	}

	.image:hover .close:active {
		opacity: 1;
		filter: alpha(opacity=100);
	}
	</style>
</head>
<body>

<h1>My Gallery</h1>
<?php foreach( $rows as $row ) : ?>
	<div class="image" style="background-image: url('hasilupload/<?= $row["gambar"];?>')"> 
		<a class="close" href="hapus.php?id=<?= $row["id"]; ?>" onclick="return confirm('Gambar akan dihapus?');">&times;</a>
		<span class="caption"><?= $row["caption"]; ?></span>
	</div>
<?php endforeach; ?>

</body>
</html>