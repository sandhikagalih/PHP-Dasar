<?php

$config = array(
	"HOST"		=> "localhost",
	"USERNAME"	=> "root",
	"PASSWORD"	=> "root",
	"DB"		=> "043040023"
);



function koneksi($config) {
	$conn = mysqli_connect($config["HOST"], $config["USERNAME"], $config["PASSWORD"]) or die("Koneksi ke Database Gagal");

	mysqli_select_db($conn, $config["DB"]) or die("Database Salah!");

	return $conn;
}



function query($conn, $query) {
	$result = mysqli_query($conn, $query);

	$rows = array();
	while ( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}

	return $rows;
}



function query_update($conn, $query) {
	$result = mysqli_query($conn, $query);

	$row = mysqli_fetch_assoc($result);

	return $row;
}



function delete($id, $conn) {
	// new
	$query = "select foto from mahasiswa where id = '".$id."'" ;
	$hasil = query($conn, $query);
	foreach ( $hasil as $row ) {
		if ( $row["foto"] != "nophoto.jpg" )
			unlink("../../../images/foto/" . $row['foto']); 
	}

	mysqli_query($conn, "DELETE FROM mahasiswa WHERE id='$id'");
	return mysqli_affected_rows($conn);

}



function tambah($hasil, $conn, $nama_file) {
	extract($hasil);
	$nama = htmlspecialchars($nama);
	$universitas = htmlspecialchars($universitas);
	$kota = htmlspecialchars($kota);

	// new
	$foto = ( empty($nama_file) ) ?
			 "nophoto.jpg" : upload($_FILES['fupload']['name']);
	mysqli_query($conn, "
		INSERT INTO mahasiswa
			VALUES ('', '$nama', '$universitas', '$kota', '$fakultas', '$jurusan', '$foto')
	");

	return mysqli_affected_rows($conn);
}



function ubah($hasil, $id, $conn) {

	extract($hasil);
	$nama = htmlspecialchars($nama);
	$universitas = htmlspecialchars($universitas);
	$kota = htmlspecialchars($kota);
	
	// new
	$foto = $_FILES['fupload']['name'] ;
	if( empty($foto) ) {
		mysqli_query($conn, "
			UPDATE mahasiswa
				SET 
					nama 	 	= '$nama',
					universitas = '$universitas',
					kota		= '$kota',
					fakultas 	= '$fakultas',
					jurusan	 	= '$jurusan'
			WHERE id = $id; 
		");
	} else {
		$foto_new = upload($nama, $_FILES['fupload']['name']);
		mysqli_query($conn, "
			UPDATE mahasiswa
				SET 
					nama 	 	= '$nama',
					universitas = '$universitas',
					kota		= '$kota',
					fakultas 	= '$fakultas',
					jurusan	 	= '$jurusan',
					foto		= '$foto_new'
			WHERE id = $id; 
		");
	}

	return mysqli_affected_rows($conn);
}



function cek($id_yang_dicek, $id_sekarang) {
	echo ( $id_yang_dicek == $id_sekarang ) ? "selected" : "";
}



function cek_gambar($nama_file_lama) {
	if( empty($nama_file_lama) )
		return true;

	$gambar_yang_diperbolehkan = array("jpg", "jpeg", "png", "gif", "wbmp");
	$nama_file_baru = explode(".", $nama_file_lama);
	$ekstensi_file = $nama_file_baru[1];

	return ( in_array($ekstensi_file, $gambar_yang_diperbolehkan) ) ?
		true : false;

}



// new
function upload($fupload_name){
  //direktori gambar
  $vdir_upload = "../../../images/foto/";
  $vfile_upload = $vdir_upload . $fupload_name;
  $tipe_file   = $_FILES['fupload']['type'];

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);

  //identitas file asli  
  if ( $tipe_file == "image/jpeg" ) {
      $im_src = imagecreatefromjpeg($vfile_upload);
  } elseif ( $tipe_file == "image/png" ) {
      $im_src = imagecreatefrompng($vfile_upload);
  } elseif ( $tipe_file == "image/gif" ) {
      $im_src = imagecreatefromgif($vfile_upload);
  } elseif ( $tipe_file == "image/wbmp" ) {
      $im_src = imagecreatefromwbmp($vfile_upload);
    }
  $src_width = imageSX($im_src);
  $src_height = imageSY($im_src);

  //Simpan dalam versi small 100 pixel
  //Set ukuran gambar hasil perubahan
  $dst_width = 100;
  $dst_height = 100;

  //proses perubahan ukuran
  $im = imagecreatetruecolor($dst_width,$dst_height);
  imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);


  $ext = str_replace("image/",".", $_FILES["fupload"]["type"]);

  // buat nama baru untuk file
  $nama = strval(time());

  //Simpan gambar
  if ( $_FILES["fupload"]["type"] == "image/jpeg" ) {
      imagejpeg($im, $vdir_upload . $nama . $ext);
  } elseif ( $_FILES["fupload"]["type"] == "image/png" ) {
      imagepng($im, $vdir_upload . $nama . $ext);
  } elseif ( $_FILES["fupload"]["type"] == "image/gif" ) {
      imagegif($im, $vdir_upload . $nama . $ext);
  } elseif( $_FILES["fupload"]["type"] == "image/wbmp" ) {
      imagewbmp($im, $vdir_upload . $nama . $ext);
  }
  
  //Hapus gambar di memori komputer
  imagedestroy($im_src);
  imagedestroy($im);

  // hapus file lama
  unlink ($vfile_upload);

  return $nama . $ext ;

}
?>