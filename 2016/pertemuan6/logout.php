<?php 
// jalankan session
session_start();
// hapus session
session_destroy();

// hapus keynya
$_SESSION = array();

// hapus cookie
setcookie("username", "", time() - 3600);
setcookie("password", "", time() - 3600);

// kembalikan ke halaman login
header("Location: login.php");
exit;

?>