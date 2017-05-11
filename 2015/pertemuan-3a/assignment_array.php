<?php 
/*
ARRAY
Assignment / Pengisian Array
*/ 

$teman = array("arief", "budi", "ratih", "doddy", "susan");

// Pengisian Array 1
// menyisipkan elemen baru pada array sesuai dengan index-nya
// jika kita tau indexnya
// cth: menyisipkan 'amanda' setelah budi, budi adalah elemen dengan index 1
// jadi kita sisipkan elemen baru di index 2
// elemen selanjutnya akan bergeser index nya

echo "Pengisian array berdasarkan index: <br><br>";

echo "Array awal: <br>";
print_r($teman);
echo "<br><br>";

echo "Menambahkan 'amanda' pada array di index ke-2 <br><br>";
$teman[2] = "amanda";

echo "Array akhir: <br>";
print_r($teman);

echo "<hr><br>";


// Pengisian Array 2
// menambahkan elemen baru di akhir array
// kita tidak perlu tau index-nya
// cth: menambahkan 'arry' pada akhir array

echo "Pengisian elemen baru diakhir array: <br><br>";

echo "Array awal: <br>";
print_r($teman);
echo "<br><br>";

echo "Menambahkan 'arry' pada akhir array<br><br>";
$teman[] = "arry";

echo "Array akhir: <br>";
print_r($teman);

?>