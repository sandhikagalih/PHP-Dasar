mvc/4/README

Sandhika Galih
sandhikagalih@unpas.ac.id
Pemrograman Web II 20132014

* menampilkan 5 halaman HTML statis
* menambahkan halaman footer yang di require di tiap-tiap halaman utama
* menambahkan halaman functions.php yang berisi semua fungsi-fungsi yang dibutuhkan
* halaman functions.php di require di tiap-tiap halaman 
* mengganti pemanggilan require header di tiap-tiap halaman dengan fungsi render_header()
* render_header() berfungsi memanggil halaman header dengan judul halaman yang berbeda untuk tiap halaman yang memanggilnya
* mengganti pemanggilan require sidebar di tiap-tiap halaman dengan fungsi render_sidebar()
* render_sidebar() berfungsi memanggil halaman sidebar, dengan menampilkan sidebar sebagai array sehingga memudahkan jika akan dilakukan penambahan halaman baru
* merapihkan seluruh file ke dalam folder agar membuat halaman web lebih terorganisir
* menerapkan MVC terhadap struktur direktori
* mengubah seluruh path pada tiap-tiap halaman karena sekarang banyak file yang sudah berubah tempat
* mengubah nama halaman index.php menjadi home.php
* menambahkan file index.php baru yang berfungsi me-redirect halaman ke home.php


css/
images/
controllers/
	home.php
	products.php
	services.php
	contact.php
	about.php
views/
	header.php - header untuk semua halaman
	footer.php - footer untuk semua halaman
	sidebar.php - sidebar untuk semua halaman
helpers/
	functions.php - halaman yang berisi fungsi-fungsi yang dibutuhkan
index.php - berfungsi me-redirect ke halaman home.php