<!doctype html>
<html>
<head>
	<title>Mailing List</title>
	<style>
		ul, li { margin: 0; padding: 0; }
		li { list-style: none; }
		.warning { color: red; font-style: italic; }
	</style>
</head>
<body>

<h1>Pendaftaran Keanggotaan</h1>
<form action="" method="post">
	<?php if ( isset($status) ): ?>
	<p class="warning"><?= $status; ?></p>
	<?php endif; ?>
	<ul>
		<li>
			<label for="nama">Nama Anda:</label>
			<input type="text" name="nama" id="nama" value="<?= ingat_input("nama"); ?>" required>
		</li>
		<li>
			<label for="email">Alamat Email:</label>
			<input type="email" name="email" id="email" value="<?= ingat_input("email"); ?>" required>
		</li>
		<li>
			<input type="submit" name="submit" value="Daftar!">
		</li>
	</ul>
</form>

</body>
</html>