<?php 
// sebelum bekerja dengan session,
// jalankan fungsi session_start(); terlebih dahulu
session_start();

// matikan akses ke variabel $_SESSION
session_destroy();

// hapus isi array $_SESSION
$_SESSION = array();
?>