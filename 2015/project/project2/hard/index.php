<?php
session_start();

// cek apabila user mencoba mengakses langsung halaman ini
if (!isset($_SESSION["username"])) {
	header("Location: controllers/login.php");
} else {
	header("Location: controllers/main.php");
}


?>