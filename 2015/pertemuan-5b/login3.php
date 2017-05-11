<?php
/**
 * login3.php
 *
 * Sandhika Galih
 * sandhikagalih@unpas.ac.id
 *
 * implementasi user login sederhana.
 * data dikirim ke halaman ini sendiri menggunakan $_SERVER["PHP_SELF"]
 * jika user berhasil login, redirect ke halaman admin0.php
 * implementasi session
 * tambahkan fungsi session_start(); di awal halaman (sebelum elemen html apapun)
 * akses data dengan superglobals $_SESSION
 * menambahkan COOKIE untuk mengingat data user meskipun session sudah berakhir
 * melakukan pengecekan apakah cookie masih ada atau sudah expire
 * jika masih ada, redirect ke halaman admin3.php
 * cara ini kurang baik karena kita menyimpan informasi penting (password), di komputer client
 * cara ini baik jika menggunakan SSL / HTTPS
 *
 */

session_start();

// data user ini hanya untuk demo, pada kenyataannya nanti menggunakan database
define("USERNAME", "admin");
define("PASSWORD", "motekar");

// cek apabila cookie masih ada
if ( isset($_COOKIE["username"]) && isset($_COOKIE["password"]) ) {
  // cek apabila cookie sesuai dengan user login
  if ( ($_COOKIE["username"] == USERNAME) && ($_COOKIE["password"] == PASSWORD) ) {
    // set session user menggunakan cookie
    $_SESSION["username"] = $_COOKIE["username"];

    // redirect user ke halaman admin
    header("Location: admin3.php");
  }
}


// cek apabila tombol submit sudah di-klik / form telah di-submit
if (isset($_POST["submit"])) {
    // simpan data $_POST ke dalam variabel
    $username = $_POST["username"];
    $password = $_POST["password"];

    // validasi login user
    if ($username == USERNAME && $password == PASSWORD) {
      // login berhasil

      // set session user, variabel yang dapat digunakan di semua halaman
      $_SESSION["username"] = $username;

      // set cookie untuk username dan password selama 1 minggu
      setcookie("username", $username, time() + 60 * 60 * 24 * 7);
      setcookie("password", $password, time() + 60 * 60 * 24 * 7);

      // redirect user ke halaman admin
      header("Location: admin3.php");
      exit;
  
    } else {
      // login gagal

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
<body style="text-align: center;">
<?php if (isset($error)): ?>
  <div style="color: red; font-style: italic;">Login Gagal!</div>
<?php endif ?>

<h1>Form Login</h1>

<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
  <table align="center">
    <tr>
      <td>Username:</td>
      <td>
        <?php if ( isset($_POST["username"]) ) : ?>
          <input name="username" type="text" value="<?= htmlspecialchars($_POST["username"]); ?>"></td>
        <?php else : ?>
          <input name="username" type="text" value=""></td>
        <?php endif ?>
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