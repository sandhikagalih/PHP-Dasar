	
	// inisialisasi variabel xhr untuk diisi objek ajax
	// nama variabel boleh apa saja
	// xhr untuk XMLHttpRequest
	var xhr = null;
	// pengecekan browser
	if( window.XMLHttpRequest ) {
		// instansiasi ajax untuk browser selain IE
		xhr = new XMLHttpRequest();
	} else {
		// instansiasi ajax untuk IE
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}
	// jika browser tidak mendukung ajax
	if( xhr == null ) {
		return alert("browser tidak mendukung ajax!");
	}

	// jika ajax sudah siap mengirimkan data
	xhr.onreadystatechange = function() {
		if( (xhr.readyState == 4) && (xhr.status == 200) ) {
			// ambil data dari halaman tujuan sebagai text
			// lalu tampilkan hasilnya ke dalam 'elemen'
			document.getElementById('elemen').innerHTML = xhr.responseText;
		}
	}

	// eksekusi ajax
	// mengirimkan permintaan ajax menggunakan method ke url tujuan
	// true artinya asynchronous
	xhr.open("method", "url", true);
	xhr.send();



	