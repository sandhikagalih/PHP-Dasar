<?php 
$matriks = [
		[1,2,3],
		[4,5,6],
		[7,8,9]
	];
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

	<?php 
		for( $i = 0; $i < count($matriks); $i++ ) {
			for( $j = 0; $j < count($matriks[$i]); $j++) {
				echo "<div class='kotak'>" . $matriks[$i][$j] . "</div>";
			}
			echo "<div class='clear'></div>";
		}
	?>






</body>
</html>