<?php
/*******************************************************************************
 * register2.php
 *
 * Sandhika Galih
 * sandhikagalih@unpas.ac.id
 *
 * Implementasi pendaftaran asisten, data didapat dari file daftar_asisten1.php
 * Memberitahu user ketika ada input yang tidak diisi, 
 * Lalu tambahkan link untuk kembali ke daftar_asisten2.php
 ********************************************************************************/

?>

<!doctype html>
<html>
  <head>
    <title>Asisten</title>
  </head>
  <body style="text-align: center;">
    <?php if (empty($_POST["nrp"]) || empty($_POST["nama"]) || empty($_POST["angkatan"])) { ?>
      <h2>Harap isi semua input!</h2>
      <h3><a href="daftar_asisten2.php">Kembali</a></h3>
    <?php  } else { ?>
      <h1>Anda telah terdaftar! (Ceritanya..)</h1>
    <? } ?>
  </body>
</html>