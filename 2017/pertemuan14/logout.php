<?php 
session_start();
session_destroy();

// hapus cookie
setcookie('id', '', time() - 3600);
setcookie('hash', '', time() - 3600);

header("Location: login.php");
exit;
?>