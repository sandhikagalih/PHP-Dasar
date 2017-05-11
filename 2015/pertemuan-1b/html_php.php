<!doctype html>
<html>
<head>
	<title>Coba PHP dalam HTML</title>
</head>
<body>
	<!-- Sintaks PHP boleh berada di dalam HTML -->
	<h1><?php echo "Ini dihasilkan dari PHP"; ?></h1>

	<?php 

		// Sintaks HTML juga boleh berada di dalam PHP

	    echo "<ul>
	    		<li>Ini</li>
	    		<li>Juga</li>
	    		<li>Dihasilkan</li>
	    		<li>dari</li>
	    		<li>PHP</li>
	    	 </ul>";
	?>

</body>
</html>