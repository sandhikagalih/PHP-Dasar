<?php 

if( isset($_POST["submit"]) ) {
	//var_dump($_FILES["file"]["type"]); die;

	$nama = $_FILES["file"]["name"];
	$tipe = $_FILES["file"]["type"];
	$temp = $_FILES["file"]["tmp_name"];
	$ukuran = $_FILES["file"]["size"];

	$ekstensi = array("jpeg", "jpg", "gif", "png");

	$file_explode = explode(".", $nama);
	$file_ext = strtolower(end($file_explode));

	if( !in_array($file_ext, $ekstensi) ) {
		
		// jika file bukan image
		echo "jenis file salah!";
	} else {
		// jika benar file image
		
		// cek ukuran < 1MB
		if( $ukuran > 1048576 ) {
			// jika lebih besar
			echo "ukuran file terlalu besar!";
		} else {
			// lebih kecil dari 1MB
			// lakukan upload
			
			if ( move_uploaded_file($temp, "hasilupload/" . $nama) ) {
				echo "Upload Berhasil!";
			} else {
				echo "Upload Gagal!";
			}
		}
	}

	

}
 
?>

<!doctype html>
<html>
<head>
	<title>Upload</title>
</head>
<body>

<form action="" method="post" enctype="multipart/form-data">
	
	<input type="file" name="file">
		<br>
	<input type="submit" name="submit" value="Upload">

</form>

</body>
</html>