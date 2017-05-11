<?php
require 'functions.php';
$conn = konek();

if( isset($_FILES["image"]) ) {
	if ( !cek_gambar($_FILES["image"]["name"], $_FILES["image"]["type"]) ) {
		$status = "
				<script>
					alert('Jenis file salah, harap pilih file gambar');
				</script>
			";

	} else {
		// mengecek apakah file lebih besar dari 1MB
		if( $_FILES["image"]["size"] > 1048576 ) {
			$status = "
				<script>
					alert('Ukuran file terlalu besar');
				</script>
			";

		} else {
			// upload file
			if ( upload_gambar($_FILES["image"]["tmp_name"], "hasilupload/", $_FILES["image"]["name"], $_POST["caption"], $conn ) ) {
				$status = "
							<p>Image Berhasil Diupload</p>
						";
			} else {
				$status = "<p>image gagal diupload</p>";
			}
		}
	}


}
 
?>



<!doctype html>
<html>
<head>
	<title>Upload Image</title>
	<style>
	p { color: red; font-style: italic; }
	.image img { width: 100px; }
	.image {
		width: 100px;
		height: 100px;
		overflow: hidden;
	}
	</style>
</head>
<body>
<div><?= ( isset($status) ) ? $status : ""; ?></div>
<form action="" method="post" enctype="multipart/form-data">
	
	<label for="image">
		<input type="file" name="image" id="image">
	</label>
	<br>
	<label for="caption">Caption: </label>
	<input type="text" name="caption" id="caption">
	<br>
	<input type="submit" name="submit" value="upload">

</form>

</body>
</html>