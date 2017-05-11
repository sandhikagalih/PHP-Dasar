<?php 

$conn = mysqli_connect('localhost', 'root', 'root'); 
mysqli_select_db($conn, '043040023');

$query1 = "
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
		m.jurusan  = j.id
	ORDER BY nama ASC
";

if ( !isset($_GET["cari"]) ) {
	$result = mysqli_query($conn, $query1);
	while ( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}
} else {
	$cari = $_GET["cari"];
	$sorting = $_GET["sorting"];
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
		ORDER BY $sorting ASC
			
	";
	$result = mysqli_query($conn, $query2);
	while ( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}
}

?>

<!doctype html>
<html>
<head>
	<title>Friends List</title>
	<link rel="stylesheet" href="css/font.css">
	<link rel="stylesheet" href="css/gumby.css">
	<link rel="stylesheet" href="css/style.css">
	<script src="js/jquery.js"></script>
	<script>
	$(document).ready(function() {
		$("#cari").keyup(function() {
			var keyword = $("#cari").val();
			var sorting = $("#sorting").val();
			$.ajax({
				url: 'search.php',
				type: 'GET',
				data: { 
					cari: keyword,
					sorting: sorting
				},
				success: function(res) {
					$("#result").html(res);
				}
			});
			
		});

		$("#sorting").change(function() {
			var keyword = $("#cari").val();
			var sorting = $("#sorting").val();
			$.ajax({
				url: 'search.php',
				type: 'GET',
				data: { 
					cari: keyword,
					sorting: sorting
				},
				success: function(res) {
					$("#result").html(res);
				}
			});
			
		});
	});
	</script>
</head>
<body>

<h2>Daftar Teman</h2>

<div class="container">
	<div class="search">
		<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="get">
			<ul>
				<li class="prepend append field">
				    <span class="adjoined"><i class="icon-search"></i></span>
				    <input class="wide text input" type="text" name="cari" id="cari" placeholder="Cari teman ..." autocomplete="off" autofocus />
				    <div class="medium primary btn"><input type="submit" value="Cari"></div>
				</li>
				<li>
					<label for="sorting">Urutkan Berdasarkan: </label>
					<div class="picker">
					    <select name="sorting" id="sorting">
					      <option value="nama">Nama</option>
					      <option value="universitas">Universitas</option>
					      <option value="jurusan">Jurusan</option>
					    </select>
					  </div>
				</li>
			</ul>
		</form>
	</div>
	
	<div id="result">
		
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

	</div>

</div>

</body>
</html>