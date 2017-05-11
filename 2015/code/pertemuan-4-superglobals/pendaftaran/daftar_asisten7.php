<? 
/*************************************************************
 * daftar_asisten7.php
 *
 * Sandhika Galih
 * sandhikagalih@unpas.ac.id
 *
 * Implementasi registrasi asisten laboratorium
 * Mengirim data ke daftar_asisten7.php (ke halaman ini sendiri)
 * Tambahkan name="kirim" pada tombol submit
 * $_POST["kirim"] adalah data yang dikirimkan ketika tombol submit diklik
 * Men-generate daftar combobox angkatan menggunakan array
 * Tetap memunculkan isi inputan ketika sebelumnya ada yang error
 * Agar user tidak mengetik ulang kembali semua input
 *****************************************************************/

// array angkatan
$ANGKATAN = array(
  "2007",
  "2008",
  "2009",
  "2010",
  "2011",
  "2012",
  "2013"
);

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
      <form action="daftar_asisten7.php" method="post">
        <table style="border: 0; margin-left: auto; margin-right: auto; text-align: left">
          <tr>
            <td>NRP:</td>
            <td><input name="nrp" type="text" value="<?php if(isset($_POST["nrp"])) echo $_POST["nrp"]; ?>"></td>
          </tr>
          <tr>
            <td>Nama:</td>
            <td><input name="nama" type="text" value="<?php if(isset($_POST["nama"])) echo htmlspecialchars($_POST["nama"]); ?>"></td>
          </tr>
          <tr>
            <td>Jenis Kelamin:</td>
            <td>
              <?php 
                function cek_laki2() {
                  if (isset($_POST["gender"])) {
                    return ($_POST["gender"] == "L") ? "checked" : "";
                  } else {
                    return "checked";
                  }
                } 

                function cek_perempuan() {
                  if (isset($_POST["gender"])) {
                     return ($_POST["gender"] == "P") ? "checked" : "";
                  } else {
                    return "";
                  }
                } 
              ?>
              <input name="gender" type="radio" value="L" <?= cek_laki2(); ?>> Laki-Laki
              <input name="gender" type="radio" value="P" <?= cek_perempuan(); ?>> Perempuan 
            </td>
          </tr>
          <tr>
            <td>Angkatan:</td>
            <td>
              <select name="angkatan">
                <option value=""></option>
                <? 
                    foreach ($ANGKATAN as $tahun)
                    {
                        if (isset($_POST["angkatan"]) && $_POST["angkatan"] == $tahun)
                            echo "<option selected='selected' value='$tahun'>$tahun</option>";
                        else
                            echo "<option value='$tahun'>$tahun</option>";
                    }
                ?>
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
