<?php
/**
 * login0.php
 *
 * Sandhika Galih
 * sandhikagalih@unpas.ac.id
 *
 * implementasi user login sederhana.
 * data dikirim ke halaman ini sendiri menggunakan $_SERVER["PHP_SELF"]
 * jika user berhasil login, redirect ke halaman admin0.php
 *
 */

// data user ini hanya untuk demo, pada kenyataannya nanti menggunakan database
define("USERNAME", "admin");
define("PASSWORD", "motekar");

// cek apabila tombol submit sudah di-klik / form telah di-submit
if (isset($_POST["submit"])) {
    // apabila username & password valid, user diperbolehkan login
    if ($_POST["username"] == USERNAME && $_POST["password"] == PASSWORD) {
        // redirect user ke halaman admin
        header("Location: admin0.php");
        exit;
    } else {
      // apabila username & password kosong atau tidak sesuai
      $error = true;
    }
}
?>

<!doctype html>
<html>
<head>
  <title>Log In</title>
</head>
<body align="center">
<?php if (isset($error)): ?>
  <div style="color: red; font-style: italic;">Login Gagal!</div>
<?php endif ?>

<h1>Form Login</h1>

<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
  <table align="center">
    <tr>
      <td>Username:</td>
      <td><input name="username" type="text"></td>
    </tr>
    <tr>
      <td>Password:</td>
      <td><input name="password" type="password"></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="submit" name="submit" value="Log In"></td>
    </tr>
  </table>      
</form>
</body>
</html>