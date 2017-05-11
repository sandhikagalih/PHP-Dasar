<?php 
/*
-------
Manipulasi Elemen Pada Array
-------
array_pop()     - menghapus / mengambil elemen TERAKHIR dari array
array_push() 	- menambahkan beberapa elemen pada AKHIR array
array_shift()   - menghapus / mengambil elemen PERTAMA dari array
array_unshift() - menambahkan beberapa elemen pada AWAL array
*/ 


$bulan = array("januari", "februari", "maret", "april");

echo "array awal: <br>";
print_r($bulan);
echo "<br><hr><br>";

// array_pop($array);
echo "array setelah di pop: <br>";
$hasil = array_pop($bulan);
print_r($bulan);
echo "<br>Elemen yang di pop: $hasil <br>";

// array_push($array, 'value1', 'value2', 'dst..');
// jika yang ditambahkan hanya 1 value, cukup gunakan $array[] saja
echo "<br> array setelah di push: <br>";
array_push($bulan, "mei", "juni", "juli");
print_r($bulan);

echo "<br><hr><br><br>";

// array_shift($array);
echo "array setelah di shift: <br>";
$hasil = array_shift($bulan);
print_r($bulan);
echo "<br>Elemen yang di shift: $hasil <br>";

// array_unshift($array, 'value1', 'value2', 'dst..');
// jika yang ditambahkan hanya 1 value, cukup gunakan $array[] saja
echo "<br> array setelah di push: <br>";
array_unshift($bulan, "ini", "elemen", "yang", "ditambahkan");
print_r($bulan);

?>