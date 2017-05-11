<?php

// jalankan dulu session
session_start();

// hapus akses pada session
session_destroy();

// inisiasi ulang session dengan array kosong
$_SESSION = array();

// redirect user kembali ke halaman login1.php
header("Location: ../");

?>