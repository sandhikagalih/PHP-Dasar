var huruf = document.getElementById('film'),
	button = document.getElementById('pilih');

button.style.display = "none";

huruf.onchange = function(event) {
	var xhr = null;

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
				document.getElementById('container').innerHTML = xhr.responseText;
		}
	};

	xhr.open("POST", "daftar_film.php", true);
	xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhr.send("huruf=" + huruf.value);


	event.preventDefault();
};

function tampilDeskripsi(id) {
	var xhr = null;

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
				document.getElementById('container').innerHTML = xhr.responseText;
		}
	};

	xhr.open("GET", "detail_film.php?id=" + id, true);
	xhr.send();

}