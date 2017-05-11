<?php 
// sebelum bekerja dengan session,
// jalankan fungsi session_start(); terlebih dahulu
session_start();

// cetak isi variabel superglobal,
// yang sebelumnya sudah di set
echo $_SESSION["nama"]; 
?>