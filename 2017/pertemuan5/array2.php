<?php 
$angka = [24, 2, 10, 9, 44, 11, 6];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Latihan</title>
	<style>
		.kotak {
			width: 30px;
			height: 30px;
			background-color: pink;
			text-align: center;
			line-height: 30px;
			font-size: 12px;
			margin: 2px;
			float: left;
			transition: 1s;
		}
		.kotak:hover {
			transform: rotate(360deg);
			background-color: limegreen;
			border-radius: 50%;
		}
		.clear {
			clear: both;
		}
	</style>
</head>
<body>
	
	<?php for( $i = 0; $i < count($angka); $i++ ) { ?>
		<div class="kotak"><?php echo $angka[$i]; ?></div>
	<?php } ?>
		<br><br>
	
	<?php foreach( $angka as $a ) { ?>
		<div class="kotak"><?php echo $a; ?></div>
	<?php } ?>
		<br><br>

	<?php foreach( $angka as $a ) : ?>
		<div class="kotak"><?php echo $a; ?></div>
	<?php endforeach; ?>











</body>
</html>