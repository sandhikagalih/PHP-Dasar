<?php
$a = "halo";
$halo = "Halo semua.";


// Menampilkan isi dari variabel $a
// 'halo'
echo $a . "<br />";

// Menampilkan isi dari variabel $halo
// 'Halo semua.'
echo $halo . "<br />";

// menampilkan isi dari variabel dengan nama yang sama dengan isi variabel $a
// isi variabel $a = halo, jadi menampilkan isi dari variabel $halo
// 'Halo semua.'
echo $$a . "<br />";

?>
