<?php 
require "helpers/functions.php";

if( !isset($_GET["tombol_cari"]) ) {
	$query = "SELECT 
				m.id, 
			    m.nama, 
			    m.email, 
			    j.nama as jurusan, 
			    u.nama as universitas, 
			    m.gambar
			FROM mahasiswa m, jurusan j, universitas u
			WHERE 
				jurusan = id_jurusan AND
			    universitas = id_universitas";
	// die(var_dump($query));
	$mahasiswa = query($query);
} else {
	$cari = $_GET["cari"];
	$query = "SELECT 
				m.id, 
			    m.nama, 
			    m.email, 
			    j.nama as jurusan, 
			    u.nama as universitas, 
			    m.gambar
			FROM mahasiswa m, jurusan j, universitas u
			WHERE 
				jurusan = id_jurusan AND
			    universitas = id_universitas AND
				(m.nama LIKE '%$cari%' OR
				m.email LIKE '%$cari%' OR
				j.nama LIKE '%$cari%' OR
				u.nama LIKE '%$cari%')";
	$mahasiswa = query($query);
}

$judul_halaman = "Daftar Mahasiswa";
require 'templates/header.php';
?>

<h2>Daftar Mahasiswa</h2>

<div class="search">
	<form action="" method="get">
		<ul>
			<li class="prepend append field">
			    <span class="adjoined"><i class="icon-search"></i></span>
			    <input class="wide text input" type="text" name="cari" id="cari" placeholder="Cari mahasiswa ..." autocomplete="off" autofocus />
			    <div class="medium primary btn"><input type="submit" value="Cari" name="tombol_cari"></div>
			  </li>
		</ul>
	</form>
</div>
	
<div class="container">

	<?php if( !empty($mahasiswa) ) : ?>

		<?php foreach( $mahasiswa as $mhs ) : ?>
			<div class="frame">
				<img src="img/<?php echo $mhs["gambar"]; ?>">
				<a href="profil.php?id=<?php echo $mhs["id"]; ?>">
					<span class="nama"><?php echo $mhs["nama"]; ?></span>
				</a>
				<span class="email"><?php echo $mhs["email"]; ?></span>
				<span class="jurusan"><?php echo $mhs["jurusan"]; ?><span>
				<span class="universitas"><?php echo $mhs["universitas"]; ?></span>

				<div class="clearfix"></div>
			</div>
		<?php endforeach; ?>

	<?php else : ?>
		<div class="frame">
			<h4 align="center">data mahasiswa tidak ditemukan</h4>
		</div>
	<?php endif; ?>

</div>
<script src="js/script.js"></script>
</body>
</html>







