var form = document.getElementById('form-komentar');

form.onsubmit = function(event) {
	var xhr = null,
		nama = document.getElementById('nama'),
		pesan = document.getElementById('komentar');

	if( window.XMLHttpRequest ) {
		xhr = new XMLHttpRequest();
	} else {
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if( xhr === null ) {
		alert("browser tidak mendukung ajax!");
		return;
	}

	xhr.onreadystatechange = function() {
		if( (xhr.readyState == 4) && (xhr.status == 200) ) {
			document.getElementById('daftar-komentar').innerHTML = xhr.responseText;
		}
	};

	xhr.open("POST", "simpan_komentar.php", true);
	xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhr.send("nama=" + nama.value + "&komentar=" + pesan.value);

	nama.value = '';
	pesan.value = '';
	event.preventDefault();
};