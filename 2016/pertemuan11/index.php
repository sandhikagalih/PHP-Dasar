<?php 
require "helpers/functions.php";

if( isset($_GET["tombol_cari"]) ) {
	$cari = $_GET["cari"];
	$query = "SELECT 
				m.id,
				m.nama,
				m.email,
				m.gambar,
				j.nama as jurusan,
				u.nama as universitas
			  FROM mahasiswa m, jurusan j, universitas u
			  WHERE
			  	m.jurusan = j.id_jurusan AND
			  	m.universitas = u.id_universitas AND
				(m.nama LIKE '%$cari%' OR
				 m.email LIKE '%$cari%' OR
				 j.nama LIKE '%$cari%' OR
				 u.nama LIKE '%$cari%')";
	$mahasiswa = query($query);
} else {
	$query = "SELECT 
				m.id,
				m.nama,
				m.email,
				m.gambar,
				j.nama as jurusan,
				u.nama as universitas
			  FROM mahasiswa m, jurusan j, universitas u
			  WHERE
			  	m.jurusan = j.id_jurusan AND
			  	m.universitas = u.id_universitas
			  ORDER BY id ASC";
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
				<input autocomplete="off" autofocus placeholder="cari data mahasiswa..." type="text" name="cari" class="wide text input">
				<div class="medium primary btn">
					<button type="submit" name="tombol_cari">Cari</button>
				</div>
			</li>
		</ul>
	</form>
</div>

<div class="container">
	
	<?php if( empty($mahasiswa) ) : ?>
		<h4 style="text-align: center;">data mahasiswa tidak ditemukan</h4>
	<?php else : ?>

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

	<?php endif; ?>
</div>

</body>
</html>