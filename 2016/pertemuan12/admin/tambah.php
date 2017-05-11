<?php 
session_start();
if (!isset($_SESSION["username"])) {
	header("Location: login.php");
}

require '../helpers/functions.php';

$jurusan = query("SELECT * FROM jurusan");
$universitas = query("SELECT * FROM universitas");

if( isset($_POST["tambah"]) ) {
	if( tambah($_POST) > 0 ) {
		echo "<script>
				alert('data berhasil ditambahkan!');
				document.location.href = 'index.php';
			 </script>";
	} else {
		echo "<script>
				alert('data gagal ditambahkan!');
				document.location.href = 'index.php';
			 </script>";
	}
}

$judul_halaman = "Tambah Data Mahasiswa";
require '../templates/admin_header.php';
?>

<div class="small info pill-left btn icon-left entypo icon-left-open"><a href="index.php">Back</a></div>

<h1>Tambah Data Mahasiswa</h1>

<div class="form-mhs">
	<form action="" method="post">

	<ul>
		<li class="prepend field">
			<span class="adjoined"><i class="icon-user"></i></span>
	    	<input class="wide text input" type="text" name="nama" placeholder="Nama" required autocomplete="off" />
	  	</li>
	  	<li class="prepend field">
			<span class="adjoined"><i class="icon-mail"></i></span>
	    	<input class="wide text input" type="text" name="email" placeholder="Email" required autocomplete="off" />
	  	</li>
	  	<li class="field">
			<h5>Pilih Jurusan:</h5>
			<div class="picker">
				<select name="jurusan">
					<?php foreach( $jurusan as $row ) : ?>
						<option value="<?php echo $row["id_jurusan"]; ?>"><?php echo $row["nama"]; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
	  	</li>
	  	<li class="field">
			<h5>Pilih Universitas:</h5>
			<div class="picker">
				<select name="universitas">
					<?php foreach( $universitas as $row ) : ?>
						<option value="<?php echo $row["id_universitas"]; ?>"><?php echo $row["nama"]; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
	  	</li>
	  	<li class="prepend field">
			<span class="adjoined"><i class="icon-picture"></i></span>
	    	<input class="wide text input" type="text" name="gambar" placeholder="Gambar" required autocomplete="off" />
	  	</li>
		<div class="medium default pretty btn"><input type="submit" value="Tambah" name="tambah" /></div>
	</ul>

	</form>
</div>

</body>
</html>