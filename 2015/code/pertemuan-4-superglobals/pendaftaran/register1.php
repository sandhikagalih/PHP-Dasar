<?
/*******************************************************************************
 * register1.php
 *
 * Sandhika Galih
 * sandhikagalih@unpas.ac.id
 *
 * Implementasi pendaftaran asisten, data didapat dari file daftar_asisten1.php
 * Redirect user kembali ke daftar_asisten1.php ketika ada kesalahan input data
 ********************************************************************************/

// validasi jika input dari user kosong
if (empty($_POST["nrp"]) || empty($_POST["nama"]) || empty($_POST["angkatan"]))
{
    header("Location: http://localhost:8888/pw2/pertemuan-4a/pendaftaran/daftar_asisten1.php");
    exit;
}

?>

<!doctype html>
<html>
  <head>
    <title>Asisten</title>
  </head>
  <body>
      <h1>Anda telah terdaftar! (Ceritanya..)</h1>
  </body>
</html>