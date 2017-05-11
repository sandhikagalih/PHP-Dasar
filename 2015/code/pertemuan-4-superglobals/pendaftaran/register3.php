<?
/*******************************************************************************
 * register3.php
 *
 * Sandhika Galih
 * sandhikagalih@unpas.ac.id
 *
 * Implementasi pendaftaran asisten, data didapat dari file daftar_asisten3.php
 * Redirect user kembali ke daftar_asisten3.php ketika ada kesalahan input data
 * Tampilkan data registrasi
 ********************************************************************************/

// jika user mengisi semua input
if (!empty($_POST["nrp"]) || !empty($_POST["nama"]) || !empty($_POST["angkatan"]))
{
    $nrp  	   = $_POST["nrp"];
    $nama 	   = $_POST["nama"];
    $angkatan  = $_POST["angkatan"];
    $jenis_kel = ($_POST["gender"] == "L") ? "Laki-Laki" : "Perempuan";

} else {
	// jika input user ada yang kosong
	header("Location: http://localhost:8888/pw2/pertemuan-4a/pendaftaran/daftar_asisten3.php");
    exit;
}

?>

<!doctype html>
<html>
  <head>
    <title>Asisten</title>
  </head>
  <body style="text-align: center;">
    <h1>Anda telah terdaftar! (Ceritanya..)</h1>

    <h2>Berikut ini adalah data pendaftaran anda:</h2>
    <p>NRP : <?php echo $nrp; ?></p>
	<p>Nama : <?php echo $nama; ?></p>
	<p>Jenis Kelamin : <?php echo $jenis_kel; ?></p>
	<p>angkatan : <?php echo $angkatan; ?></p>
  </body>
</html>