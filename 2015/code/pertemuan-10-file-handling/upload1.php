<?php 

if( isset($_FILES["image"]) ) {

	// membuat array yang berisi ekstensi file yang diijinkan untuk diupload
	$extensions = array("jpg", "jpeg", "gif", "png");

	// simpan informasi file yang diupload sebagai variabel agar nanti mudah digunakan
	$file_name = $_FILES["image"]["name"];
	$file_size = $_FILES["image"]["size"];
	$file_tmp  = $_FILES["image"]["tmp_name"];
	$file_type = $_FILES["image"]["type"];

	// mencacah nama file berdasarkan .
	$file_explode  = explode(".", $file_name);

	// menggunakan fungsi end()
	// untuk mengambil isi array terakhir (apabila nama file mengandung ".")
	// cth : 1.coba.jpg (mengambil 'jpg')
	// setelah itu ubah string menjadi huruf kecil menggunakan fungsi strtolower()
	// untuk menangani apabila ada nama file yg ekstensinya huruf besar (cth: coba.JPG)
	$file_ext = strtolower(end($file_explode));

	// mengecek apakah ekstensi file yang diupload, ada di daftar ekstensi yang diijinkan
	// jika ada, berarti file boleh diupload
	if( ($file_type != "image/jpg")  ||
		($file_type != "image/jpeg") ||
		($file_type != "image/gif")  ||
		($file_type != "image/png")  &&
		(!in_array($file_ext, $extensions) ) {
		$status = "
				<script>
					alert('Jenis file salah, harap pilih file gambar');
				</script>
			";

	} else {
		// mengecek apakah file lebih besar dari 1MB
		if( $file_size > 1048576 ) {
			$status = "
				<script>
					alert('Ukuran file terlalu besar');
				</script>
			";

		} else {
			// upload file
			if ( move_uploaded_file($file_tmp, "hasilupload/" . $file_name) ) {
				$status = "
							<p>Image Berhasil Diupload</p>
							<div class='image'>
								<img src=\"hasilupload/$file_name\"/>
							</div>
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
	<input type="submit" name="submit" value="upload">

</form>

</body>
</html>