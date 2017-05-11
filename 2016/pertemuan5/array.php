<?php 
$mahasiswa = [
	[
		"nrp" => "043040023",
		"nama" => "Sandhika",
		"email" => "sandhikagalih@unpas.ac.id",
		"nilai" => [
			"pw2" => "A",
			"jarkom" => "B"
		]
	],
	[
		"nrp" => "03340001",
		"nama" => "Erik",
		"email" => "erik@gmail.com",
		"nilai" => [
			"pw2" => "A",
			"jarkom" => "A"
		]
	]
];

echo $mahasiswa[1]["nilai"]["jarkom"];


?>