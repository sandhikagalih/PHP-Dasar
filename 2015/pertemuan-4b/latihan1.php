<?php 
 
$teman = array(
	"anggoro" => "1200",
	"erik" => "800",
	"acep" => "912",
	"fajar" => "2500",
	"mellia" => "1112"
);

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
		
		ul { list-style: none; }

		li {
			width: 250px;
			margin: 0 0 13px 13px;
			border: 1px solid #e9eaed;
			display: inline-block;
			height: 100px;
			position: relative;
			background-color: #f6f7f8;
		} 

		li a {
			position: absolute;
			left: 110px;
			top: 20px;
			color: #3b5998;
			font-size: 13px;
			font-weight: bold;
			text-decoration: none;
		}

		li a:hover { text-decoration: underline; }

		span {
			position: absolute;
			color: #89919c;
			font-size: 10px;
			top: 38px;
			left: 110px;
		}

		h1 { margin-left: 12px; }
	</style>
</head>
<body>

<h1>My Friends</h1>
<ul>
	<?php foreach ($teman as $nama => $jml) : ?>
	<li>
		<img src="images/<?=$nama; ?>.jpg" alt="<?=$nama; ?>">
		<a href="latihan2.php?nama=<?=$nama; ?>&jumlah_teman=<?=$jml; ?>"><?=$nama; ?></a>
	</li>
	<?php endforeach; ?>
</ul>

</body>
</html>