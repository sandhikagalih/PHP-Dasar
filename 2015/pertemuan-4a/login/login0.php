<?
/**
 * login0.php
 *
 * Implementasi user login sederhana.
 *
 * Sandhika Galih
 * sandhikagalih@unpas.ac.id
 */

// data user ini hanya untuk demo, pada kenyataannya nanti menggunakan database
define("USER", "sandhika");
define("PASS", "motekar");

// cek apabila username dan password sudah di-submit
if (isset($_POST["user"]) && isset($_POST["pass"])) {
    // apabila usernam & password valid, user diperbolehkan login
    if ($_POST["user"] == USER && $_POST["pass"] == PASS) {
        // redirect user ke halaman home
        header("Location: http://localhost:8888/pw2/pertemuan-4a/login/home.php");
        exit;
    } else {
      $error = true;
    }
}
?>

<!doctype html>
<html>
<head>
  <title>Log In</title>
</head>
<body>
  <? if (isset($error)): ?>
    <div style="color: red; font-style: italic;">Login Gagal!</div>
  <? endif ?>
  <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
    <table>
      <tr>
        <td>Username:</td>
        <td><input name="user" type="text"></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input name="pass" type="password"></td>
      </tr>
      <tr>
        <td></td>
        <td><input type="submit" value="Log In"></td>
      </tr>
    </table>      
  </form>
</body>
</html>