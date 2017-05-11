<?php 

if( isset($_FILES["image"]) ) {
	// upload file
	// file apapun, ukuran berapapun
	
	// var_dump($_FILES); die;
	$file_name = $_FILES["image"]["name"];
	$file_temp = $_FILES["image"]["tmp_name"];

	move_uploaded_file($file_temp, "hasilupload/" . $file_name);
	echo "file berhasil diupload";
}
 
?>



<!doctype html>
<html>
<head>
	<title>Upload Image</title>
</head>
<body>

<form action="" method="post" enctype="multipart/form-data">
	
	<label for="image">
		<input type="file" name="image" id="image">
	</label>
	<br>
	<input type="submit" name="submit" value="upload">

</form>

</body>
</html>