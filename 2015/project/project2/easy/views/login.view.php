<?php require '../views/header.php'; ?>

<div class="login-area">

  <h1>Form Login</h1>

  <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
    <table align="center">
      <?php if (isset($error)): ?>
        <div style="color: red; font-style: italic; text-align:center; font-weight:bold;">Login Gagal!</div>
      <?php endif ?>
      <tr>
        <td colspan="2">
          <p>admin / motekar, atau</p>
          <p>sandhika / 123</p>
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


<?php require '../views/footer.php'; ?>