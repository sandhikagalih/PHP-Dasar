<?php 
 
function render_header($judul) {
	require "header.php";
}

function render_sidebar($judul) {
	$halaman = array(
		"Home" 		=> "index.php",
		"Products" 	=> "products.php",
		"Services" 	=> "services.php",
		"About"		=> "about.php",
		"Contact"	=> "contact.php"
	);
	require "sidebar.php";
}


?>