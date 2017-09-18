<?php
function tampilkan_kotak($jml_baris) {
	for( $i = 0; $i < $jml_baris; $i++ ) {
		for( $j = 0; $j <= $i; $j++ ) {
			echo "<div class='kotak'>" . ($j+1) . "</div>";
		}
		echo "<div class='clear'></div>";
	}
}
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
	
	<?php tampilkan_kotak(10); ?>
	



</body>
</html>