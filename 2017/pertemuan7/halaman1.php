<?php 
	if( !isset($_POST["submit"]) ) {
		header("Location: post.php");
		die;
	} else {
		echo "Selamat Datang, " . $_POST["nama"];
	}
		
?>