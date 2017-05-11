<?php 
/*
-------
SORTING
-------
ada banyak fungsi untuk mengurutkan isi dari array:
sort()    - mengurutkan isi array MENAIK (dari kecil ke besar, dari A ke Z)
rsort()   - mengurutkan isi array MENURUN
asort()   - mengurutkan isi dari associative array MENAIK berdasarkan VALUE
ksort()   - mengurutkan isi dari associative array MENAIK berdasarkan KEY
arsort()  - mengurutkan isi dari associative array MENURUN berdasarkan VALUE
krsort()  - mengurutkan isi dari associative array MENURUN berdasarkan KEY
*/ 

$array1 = array(4,7,12,45,64,3,22,36);
$array2 = array("budi", "arief", "ratih", "doddy", "susan");
$array3 = array(
	"budi"  => "sibuditea@yahoo.com",
	"arief" => "my_name_is_arief@gmail.com",
	"ratih" => "violet.ratih123@mail.unpas.ac.id",
	"doddy" => "doddy2112@unpas.ac.id",
	"susan" => "uchan_uchu@hotmail.com"
);

// cetak array awal
echo "ini adalah array awal: <br>";
echo "array 1: ";
print_r($array1);
echo "<br> array 2: ";
print_r($array2);
echo "<br> array 3 (Associative Array): <br>";
print_r($array3);
echo "<br><br><hr>";

echo "<br>Pengurutan Array <br>";
// sort() - mengurutkan isi array MENAIK (dari kecil ke besar, dari A ke Z)
echo "sort()<br>";
sort($array1);
print_r($array1);
echo "<br>";
sort($array2);
print_r($array2);
echo "<br><br>";

// rsort() - mengurutkan isi array MENURUN
echo "rsort()<br>";
rsort($array1);
print_r($array1);
echo "<br>";
rsort($array2);
print_r($array2);
echo "<br><br>";

// asort() - mengurutkan isi dari associative array MENAIK berdasarkan VALUE
echo "asort()<br>";
asort($array3);
print_r($array3);
echo "<br>";

// ksort() - mengurutkan isi dari associative array MENAIK berdasarkan KEY
echo "ksort()<br>";
ksort($array3);
print_r($array3);
echo "<br>";

// arsort() - mengurutkan isi dari associative array MENURUN berdasarkan VALUE
echo "arsort()<br>";
arsort($array3);
print_r($array3);
echo "<br>";

// krsort() - mengurutkan isi dari associative array MENURUN berdasarkan KEY
echo "krsort()<br>";
krsort($array3);
print_r($array3);
echo "<br>";

?>