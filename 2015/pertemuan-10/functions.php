<?php 
function cek_gambar($file_name, $file_type) {
	$extensions = array("jpg", "jpeg", "gif", "png");

	// mengambil ekstensi file
	$file_explode  = explode(".", $file_name);
	$file_ext = strtolower(end($file_explode));

	if( ($file_type == "image/jpg")  ||
		($file_type == "image/jpeg") ||
		($file_type == "image/gif")  ||
		($file_type == "image/png")  &&
		(in_array($file_ext, $extensions)) ) {
			return true;
		}

	return false;
}

function upload_gambar($file_tmp, $file_dir, $file_name, $caption, $conn) {
	// mengambil ekstensi file
	$file_explode  = explode(".", $file_name);
	$file_ext = strtolower(end($file_explode));

	// buat nama baru untuk gambar agar seragam
	// nama dibentuk dari fungsi time
	$file_name_baru = strval(time()) . "." . $file_ext;

	// upload file
	if( move_uploaded_file($file_tmp, $file_dir . $file_name_baru) ) {

		return ( insert_gambar($conn, $file_name_baru, $caption) ) ? true : false;

	}

	return false;
}

function insert_gambar($conn, $file_name, $caption) {
	mysqli_query($conn, "INSERT INTO gallery VALUES ('', '$file_name', '$caption')");

	return ( mysqli_affected_rows($conn) > 0 ) ? true : false;
}

function hapus($conn, $id) {
	// cari nama gambar di database
	$hasil = mysqli_query($conn, "SELECT gambar FROM gallery WHERE id = '$id'");
	$nama_gambar = mysqli_fetch_assoc($hasil);

	// hapus data gambar di database
	mysqli_query($conn, "DELETE FROM gallery WHERE id=$id");

	if( mysqli_affected_rows($conn) ) {
		// hapus file gambar di direktori upload
		unlink("hasilupload/" . $nama_gambar["gambar"]);
		return true;
	}
	return false;
}

function konek() {
	$conn = mysqli_connect("localhost", "root", "root") or die("Koneksi gagal");
	mysqli_select_db($conn, "coba") or die("Database salah");

	return $conn;
}

 
?>