<?php 
session_start();

// cek apabila user mencoba mengakses langsung halaman ini
if (isset($_SESSION["nrp"])) {
    header("Location: survey.php");
}

require 'helpers/functions.php';

$conn = konek();

// cek apabila tombol submit sudah di-klik / form telah di-submit
if (isset($_POST["submit"])) {
    // simpan data $_POST ke dalam variabel
    $nrp = $_POST["nrp"];
    $password = $_POST["password"];

    $users = query($conn, "SELECT * FROM users");

    foreach ($users as $user) {

        // validasi login user
        if ($nrp == $user["nrp"] && $password == $user["password"]) {
          // login berhasil

          // set session user, variabel yang dapat digunakan di semua halaman
          $_SESSION["nrp"] = $nrp;

          // redirect user ke halaman admin
          header("Location: survey.php");
          exit;
      
        } else {
          // login gagal
          // apabila nrp & password kosong atau tidak sesuai
          $error = true;
        }

    }
}

?>
<!doctype html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-theme.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="login">

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-7">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-lock"></span> Login</div>
                <?php if( isset($error) ) : ?>
                    <div class="panel-body">
                        <div class="alert alert-danger">login gagal</div>
                    </div>
                <?php endif; ?>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" action="" method="post">
                    <div class="form-group">
                        <label for="nrp" class="col-sm-3 control-label">
                            NRP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nrp" placeholder="nrp" name="nrp" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">
                            Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="form-group last">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" name="submit" class="btn btn-success btn-sm">
                                Log in</button>
                                 <button type="reset" class="btn btn-default btn-sm">
                                Reset</button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="panel-footer">
                    Tidak bisa login? <a href="http://pw2.unpas.ac.id">Hubungi asisten</a></div>
            </div>

        </div>
    </div>
</div>

<script src="js/jquery-2.0.3.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>