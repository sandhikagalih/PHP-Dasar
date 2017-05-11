<?php 
/*
-------
array_slice()
-------
fungsi untuk mencacah array menjadi array yang lebih kecil
sintaks:
	array_slice($array, $offset, $length);
	$offset diisi dengan index dari elemen array yang akan dicacah
	$length diisi dengan jumlah elemen yang akan ditampung setelah index $offset
*/ 

$bulan = array('januari', 'februari', 'maret', 'april', 'mei');

echo "Array awal: <br>";
print_r($bulan);
echo "<br><hr>";

$output1 = array_slice($bulan, 2); // menyimpan maret, april, mei
$output2 = array_slice($bulan, 3, 1); // menyimpan april
$output3 = array_slice($bulan, 0, 3); // menyimpan januari, februari, maret

echo "array_slice(\$bulan, 2) <br>";
print_r($output1);
echo "<br><br>";

echo "array_slice(\$bulan, 3, 1) <br>";
print_r($output2);
echo "<br><br>";

echo "array_slice(\$bulan, 0, 3) <br>";
print_r($output3);
echo "<br>";
?>