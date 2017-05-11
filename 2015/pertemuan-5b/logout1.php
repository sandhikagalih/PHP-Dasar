<?php
/**
 * logout.php
 *
 * Sandhika Galih
 * sandhikagalih@unpas.ac.id
 *
 * implementasi halaman logout
 * gunakan fungsi session_destroy(); untuk menghapus akses pada session
 * ketika session telah terhapus, kita tidak punya akses lagi ke variabel session
 * tapi data di dalam variabelnya tetap ada
 * untuk menghilangkannya, inisiasi ulang / isi $_SESSION dengan array()
 * tetap gunakan dulu fungsi session_start(); sebelum menghapus session
 *
 */

// jalankan dulu session
session_start();

// hapus akses pada session
session_destroy();

// inisiasi ulang session dengan array kosong
$_SESSION = array();

// redirect user kembali ke halaman login1.php
header("Location: login1.php");

?>