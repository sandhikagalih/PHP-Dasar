<?php 
session_start();
if (!isset($_SESSION["username"])) {
	header("Location: login.php");
}

require '../helpers/functions.php';

$jurusan = query("SELECT * FROM jurusan");
$universitas = query("SELECT * FROM universitas");

$id = $_GET["id"];
$mahasiswa = query("SELECT * FROM mahasiswa WHERE id = $id");
$mhs = $mahasiswa[0];

if( isset($_POST["ubah"]) ) {
	if( ubah($_POST) > 0 ) {
		echo "<script>
				alert('data berhasil diubah!');
				document.location.href = 'index.php';
			 </script>";
	} else {
		echo "<script>
				alert('data gagal diubah!');
				document.location.href = 'index.php';
			 </script>";
	}
}

$judul_halaman = "Ubah Data Mahasiswa";
require '../templates/admin_header.php';
?>

<div class="small info pill-left btn icon-left entypo icon-left-open"><a href="index.php">Back</a></div>

<h1>Ubah Data Mahasiswa</h1>

<div class="form-mhs">
	<form action="" method="post">

	<input type="hidden" name="id" value="<?php echo $mhs["id"]; ?>">

	<ul>
		<li class="prepend field">
			<span class="adjoined"><i class="icon-user"></i></span>
	    	<input value="<?php echo $mhs["nama"]; ?>" class="wide text input" type="text" name="nama" placeholder="Nama" required autocomplete="off" />
	  	</li>
	  	<li class="prepend field">
			<span class="adjoined"><i class="icon-mail"></i></span>
	    	<input value="<?php echo $mhs["email"]; ?>" class="wide text input" type="text" name="email" placeholder="Email" required autocomplete="off" />
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
	    	<input value="<?php echo $mhs["gambar"]; ?>" class="wide text input" type="text" name="gambar" placeholder="Gambar" required autocomplete="off" />
	  	</li>
		<div class="medium default pretty btn"><input type="submit" value="Ubah" name="ubah" /></div>
	</ul>

	</form>
</div>

</body>
</html>