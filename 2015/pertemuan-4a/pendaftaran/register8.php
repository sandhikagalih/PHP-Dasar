<?
/*******************************************************************************
 * register8.php
 *
 * Sandhika Galih
 * sandhikagalih@unpas.ac.id
 *
 * Implementasi pendaftaran asisten, data didapat dari file daftar_asisten8.php
 * Redirect user kembali ke daftar_asisten8.php ketika ada kesalahan input data
 * Tampilkan data registrasi
 * Perbaikan sintax echo jika ingin langsung mencetak isi variabel
 * menggunakan sintax <?= $variabel_yang_akan_dicetak_isinya; ?>
 * Sanitasi input menggunakan htmlspecialchars()
 * sebelum diberi htmlspecialchars(), coba inputkan pada textfied script dibawah ini:
 *  <div style="width: 100%; height: 100%; background-color:black; position: absolute; top: 0; left: 0; right: 0; bottom: 0;"><h1 style="text-align: center; color: white;">WEBSITE ANDA TELAH DI HACK!!! MUHAHAHAHAHA</h1></div>
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
	header("Location: http://localhost:8888/pw2/pertemuan-4a/pendaftaran/daftar_asisten8.php?error=true");
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
    <p>NRP : <?= htmlspecialchars($nrp); ?></p>
	<p>Nama : <?= htmlspecialchars($nama); ?></p>
	<p>Jenis Kelamin : <?= $jenis_kel; ?></p>
	<p>angkatan : <?= $angkatan; ?></p>
  </body>
</html>