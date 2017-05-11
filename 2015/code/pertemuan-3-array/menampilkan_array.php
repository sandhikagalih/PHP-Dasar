<?php 

// contoh array
$bulan = array("januari", "februari", "maret", "april");
$angka = array(1,2,3,4,5,6,7);


// Menampilkan seluruh isi array

// cara 1 - var_dump()
// Menampilkan seluruh isi array beserta informasi lengkap tipe data dari tiap elemennya
echo "Menampilkan seluruh isi array \$bulan dengan var_dump() : <br>";
var_dump($bulan);

echo "<br><br>";

// cara 2 - print_r()
// Menampilkan seluruh isi array tanpa informasi tipe data dari tiap elemennya
echo "Menampilkan seluruh isi array \$angka dengan print_r() : <br>";
print_r($angka);

echo "<hr>";
echo "<br>";

// ---

// menampilkan salah satu isi array
// cara menampilkannya adalah dengan mengacu pada index array nya

// contoh 1
echo "menampilkan index ke-1 dari array \$bulan: <br>";
echo $bulan[1];
echo "<br><br>";

// contoh 2
echo "menampilkan index ke-3 dari array \$angka: <br>";
echo $angka[3];
echo "<br>";

?>