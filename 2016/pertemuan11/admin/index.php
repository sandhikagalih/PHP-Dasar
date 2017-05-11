<?php 
session_start();
if (!isset($_SESSION["username"])) {
	header("Location: login.php");
}

require '../helpers/functions.php';

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
$i = 1;

$judul_halaman = "Halaman Administrator";
require '../templates/admin_header.php';
?>

<h1>Daftar mahasiswa </h1>

<div class="medium secondary pretty btn"><a href="tambah.php">Tambah Data Mahasiswa</a></div>
<div class="medium warning pretty btn logout"><a href="logout.php">Logout</a></div>

	<table class="rounded striped">
		<thead>
			<tr>
				<th>No.</th>
				<th>Aksi</th>
				<th>Gambar</th>	
				<th>Nama</th>
				<th>Email</th>
				<th>Jurusan</th>
				<th>Universitas</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $mahasiswa as $row ) : ?>
				<tr>
					<td><?= $i; ?></td>
					<td>
						<div class="small success oval btn">
							<a href="ubah.php?id=<?php echo $row["id"]; ?>">ubah</a>
						</div>
						<div class="small danger oval btn">
							<a href="hapus.php?id=<?= $row["id"]; ?>" onclick="return confirm('anda yakin?')">hapus</a>
						</div>
					</td>
					<td><img src="../img/<?= $row["gambar"]; ?>" alt="<?= $row["nama"]; ?>" width="50%"></td>
					<td><?= $row["nama"]; ?></td>
					<td><?= $row["email"]; ?></td>
					<td><?= $row["jurusan"]; ?></td>
					<td><?= $row["universitas"]; ?></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>

</body>
</html>