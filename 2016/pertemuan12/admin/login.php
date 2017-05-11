<?php
session_start();

// cek apabila user mencoba mengakses langsung halaman ini
if (isset($_SESSION["username"])) {
	header("Location: index.php");
}

require '../helpers/functions.php';

if (isset($_POST["submit"])) {

	if ($_POST["username"] == "admin" && $_POST["password"] == "12345") {

		$_SESSION["username"] = $_POST["username"];

		header("Location: index.php");
		exit;
	  
    } else {
      $error = true;
    }

}

$judul_halaman = "Login";
require '../templates/admin_header.php';
?>
	
<div class="login-area">

  <h1>Form Login</h1>

  <form action="" method="post">
    <table align="center">
      <?php if (isset($error)): ?>
        <div style="color: red; font-style: italic; text-align:center; font-weight:bold;">Login Gagal!</div>
      <?php endif ?>
      <tr>
        <td colspan="2">
          <p>admin / 12345</p>
        </td>
      </tr>
      <tr>
        <td>Username:</td>
        <td><ul><li class="field"><input class="wide text input" name="username" type="text" value="<?php if(isset($_POST["username"])) echo htmlspecialchars($_POST["username"]); ?>"></li></ul></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><ul><li class="field"><input class="wide text input" name="password" type="password"></li></ul></td>
      </tr>
      <tr>
        <td></td>
        <td><div class="medium secondary pretty btn"><input  type="submit" name="submit" value="Log In"></div></td>
      </tr>
    </table>      
  </form>
</div>

</body>
</html>