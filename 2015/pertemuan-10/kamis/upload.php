<?php 

if( isset($_POST["upload"]) ) {

	$nama_file = $_FILES["file"]["name"];
	$tipe_file = $_FILES["file"]["type"];
	$temp_penyimpanan_file = $_FILES["file"]["tmp_name"];
	$ukuran_file = $_FILES["file"]["size"];

	$file_yang_boleh_diupload = array("jpg", "jpeg", "gif", "png", "bmp");

	$file_explode = explode(".", $nama_file);
	$file_ext = strtolower(end($file_explode));

	// cek apakah gambar atau bukan
	if( !in_array($file_ext, $file_yang_boleh_diupload) ) {
		// jika bukan gambar
		echo "yang anda upload bukan gambar";
	} else {
		// jika benar gambar
		
		// cek ukuran
		if( $ukuran_file > 1000000 ) {
			// jika lebih dari 1MB
			echo "gambar terlalu besar";
		} else {
			// gambar kurang dari 1 mega
			 
			// upload gambar
			move_uploaded_file($temp_penyimpanan_file, "hasilupload/" . $nama_file);
			echo "Upload berhasil";
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
	<input type="submit" name="upload" value="Upload">

</form>

</body>
</html>