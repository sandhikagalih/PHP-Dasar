<?php
	/*
		$_SERVER
		informasi lengkap dari server kita
		yang juga dapat kita lihat di phpinfo()
		jika ingin melihat seluruh isi dari $_SERVER
		lakukan: print_r($_SERVER);
	*/

	echo "Informasi lengkap server kita:<br />";
	echo "Nama Server: ". $_SERVER['SERVER_NAME'] ."<br />";
	echo "Alamat Server: ". $_SERVER['SERVER_ADDR'] ."<br />";
	echo "Port Server: ". $_SERVER['SERVER_PORT'] ."<br />";
	echo "Document Root: ". $_SERVER['DOCUMENT_ROOT'] ."<br />";
	echo "<br />";

	echo "Detail Halaman:<br />";
	echo "Alamat file ini: ". $_SERVER['PHP_SELF'] ."<br />";
	echo "Script Filename: ". $_SERVER['SCRIPT_FILENAME'] ."<br />";
	echo "<br />";

	echo "Detail Request:<br />";
	echo "Alamat Remote: ". $_SERVER['REMOTE_ADDR'] ."<br />";
	echo "Port Remote: ". $_SERVER['REMOTE_PORT'] ."<br />";
	echo "Request URI: ". $_SERVER['REQUEST_URI'] ."<br />";
	echo "Query String: ". $_SERVER['QUERY_STRING'] ."<br />";
	echo "Metode Request: ". $_SERVER['REQUEST_METHOD'] ."<br />";
	echo "Waktu Request: ". $_SERVER['REQUEST_TIME'] ."<br />";
	echo "HTTP User Agent: ". $_SERVER['HTTP_USER_AGENT'] ."<br />";
?>
