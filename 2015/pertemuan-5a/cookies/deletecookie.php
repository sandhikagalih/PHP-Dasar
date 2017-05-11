<?php 
// menghapus cookie
// sama dengan inisialisasi / set cookie
// tapi set expirenya dengan waktu lampau
setcookie("nama", "", time() - 3600, "/pw2/pertemuan-5a/"); 
?>