<?php
/* Pertemuan 1b
   Latihan 8
   bermain dengan fungsi strstr() dan str_replace()
   mencari kemunculan sebuah kata dalam rangkaian string
   lalu mengubah kata tersebut menjadi kata yang tercoret (line-through) dengan menambahkan style
*/

$teks 		= "koefisien gesekan menunjukkan sifat kekasaran permukaan. <br>
           	   Semakin besar koefisien gesek (statik/kinetik), <br>
           	   semakin kasar antar permukaan bidang.";

$cari_kata 	= "kasar";

$cek_kata = strstr($teks, $cari_kata);

echo "Teks yang anda masukkan adalah :<br>";
if ($cek_kata) {
    $hasil = str_replace($cari_kata, "<span style=\"text-decoration: line-through;\">$cari_kata</span>", $teks);
    echo $hasil;
} else {
    echo $teks;
}
?>