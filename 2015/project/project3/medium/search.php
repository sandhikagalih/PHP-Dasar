<?php 

$conn = mysqli_connect('localhost', 'root', 'root'); 
mysqli_select_db($conn, '043040023');


$cari = $_GET["cari"];
$query2 = "
	SELECT
		m.id,
		m.foto,
		m.nama,
		m.universitas,
		m.kota,
		f.nama as fakultas,
		j.nama as jurusan
	FROM mahasiswa m, fakultas f, jurusan j
	WHERE
		m.fakultas = f.id AND
		m.jurusan  = j.id AND
		(m.universitas LIKE '%$cari%' OR
		m.nama LIKE '%$cari%' OR
		j.nama LIKE '%$cari%')

		
";
$result = mysqli_query($conn, $query2);
while ( $row = mysqli_fetch_assoc($result) ) {
	$rows[] = $row;
}

?>

	<?php if ( !isset($rows) ) : ?>

		<div class="frame">
			<h4>Data Mahasiswa Tidak Ditemukan!</h4>
		</div>

	<?php else : ?>

		<?php foreach ( $rows as $hasil ) : ?>
		<div class="frame">
			<img src="../../images/foto/<?= $hasil["foto"]; ?>" alt="<?= $hasil["nama"]; ?>">
			<span class="nama"><a href="profile.php?id=<?= $hasil["id"]; ?>"><?= $hasil["nama"]; ?></a></span>
			<span class="univ"><?= $hasil["universitas"]; ?> <span><?= $hasil["kota"]; ?></span></span>
			<span class="fakultas"><?= $hasil["fakultas"]; ?><span>
			<span class="jurusan"><?= $hasil["jurusan"]; ?><span>

			<div class="clearfix"></div>
		</div>
		<?php endforeach; ?>

	<?php endif; ?>
