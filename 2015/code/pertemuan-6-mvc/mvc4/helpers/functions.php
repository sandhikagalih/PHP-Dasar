<?php 
 
function render_header($judul) {
	require "../views/header.php";
}

function render_sidebar($judul) {
	$halaman = array(
		"Home" 		=> "home.php",
		"Products" 	=> "products.php",
		"Services" 	=> "services.php",
		"About"		=> "about.php",
		"Contact"	=> "contact.php"
	);
	require "../views/sidebar.php";
}


?>