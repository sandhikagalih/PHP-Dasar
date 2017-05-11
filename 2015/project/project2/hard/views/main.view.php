<?php require '../views/header.php'; ?>

<h1>Daftar mahasiswa </h1>

<div class="medium secondary pretty btn"><a href="tambah.php">Tambah Data Mahasiswa</a></div>
<div class="medium warning pretty btn logout"><a href="logout.php">Logout</a></div>

	<table class="rounded striped">
		<thead>
			<tr>
				<td>No.</td>
				<td>Foto</td>
				<td>Nama</td>
				<td>Universitas</td>
				<td>Kota</td>
				<td>Fakultas</td>
				<td>Jurusan</td>
				<td>Aksi</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $hasil as $row ) : ?>
				<tr>
					<td><?= $i; ?></td>
					<td><img src="../../../images/foto/<?= $row["foto"]; ?>" alt="<?= $row["nama"]; ?>" width="50%"></td>
					<td><?= $row["nama"]; ?></td>
					<td><?= $row["universitas"]; ?></td>
					<td><?= $row["kota"]; ?></td>
					<td><?= $row["fakultas"]; ?></td>
					<td><?= $row["jurusan"]; ?></td>
					<td>
						<div class="small success oval btn">
							<a href="ubah.php?id=<?= $row["id"]; ?>">ubah</a>
						</div>
						<div class="small danger oval btn">
							<a href="hapus.php?id=<?= $row["id"]; ?>" onclick="return confirm('Yakin hapus data <?= $row["nama"]; ?>?')">hapus</a>
						</div>
					</td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php require '../views/footer.php'; ?>