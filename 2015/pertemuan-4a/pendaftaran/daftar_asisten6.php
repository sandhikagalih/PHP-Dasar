<? 
/*************************************************************
 * daftar_asisten6.php
 *
 * Sandhika Galih
 * sandhikagalih@unpas.ac.id
 *
 * Implementasi registrasi asisten laboratorium
 * Mengirim data ke daftar_asisten6.php (ke halaman ini sendiri)
 * Tambahkan name="kirim" pada tombol submit
 * $_POST["kirim"] adalah data yang dikirimkan ketika tombol submit diklik
 * Tetap memunculkan isi username dan password ketika sebelumnya ada yang error
 * Agar user tidak mengetik ulang kembali username dan password
 *****************************************************************/

// ketika form telah di-submit, cek apabila ada error (input ada yang tidak diisi)
if (isset($_POST["kirim"])) {
    if (empty($_POST["nrp"]) || empty($_POST["nama"]) || empty($_POST["angkatan"]))
        $error = true;
}

?>

<!doctype html>
<html>
  <head>
    <title>Pendaftaran Asisten</title>
  </head>
  <body>
    <div style="text-align: center;">
      <h1>Pendaftaran Asisten</h1>
      <? if (isset($error)): ?>
        <div style="color: red; font-style: italic;">Anda harus mengisi semua input pada form!</div>
      <? endif ?>
      <br><br>
      <form action="daftar_asisten6.php" method="post">
        <table style="border: 0; margin-left: auto; margin-right: auto; text-align: left">
          <tr>
            <td>NRP:</td>
            <td><input name="nrp" type="text"></td>
          </tr>
          <tr>
            <td>Nama:</td>
            <td><input name="nama" type="text"></td>
          </tr>
          <tr>
            <td>Jenis Kelamin:</td>
            <td>
              <input name="gender" type="radio" value="L" checked> Laki-Laki
              <input name="gender" type="radio" value="P"> Perempuan 
            </td>
          </tr>
          <tr>
            <td>Angkatan:</td>
            <td>
              <select name="angkatan">
                <option value=""></option>
                <option value="2007">2007</option>
                <option value="2008">2008</option>
                <option value="2009">2009</option>
                <option value="2010">2010</option>
                <option value="2011">2011</option>
                <option value="2012">2012</option>
              </select>
            </td>
          </tr>
        </table>
        <br><br>
        <input type="submit" value="Daftar!" name="kirim">
      </form>
    </div>
  </body>
</html>
