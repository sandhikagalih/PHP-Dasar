<!DOCTYPE html>
<html>
<head>
	<title>Looping Kotak</title>
	<style>
		.kotak {
			width: 40px;
			height: 40px;
			background-color: pink;
			font-family: arial;
			color: white;
			text-align: center;
			line-height: 40px;
			float: left;
			margin: 2px;
			transition: .3s transform ease-in-out;
		}
		.kotak:hover {
			transform: rotate(360deg);
		}
		.clear {
			clear: both;
		}
	</style>
</head>
<body>

<?php 
	for ($i=1; $i <= 10; $i++) { 
		
		for ($j=1; $j <= $i; $j++) { 
			echo "<div class=\"kotak\">$j</div>";
		}

		echo "<div class=\"clear\"></div>";

	}

?>









	
</body>
</html>