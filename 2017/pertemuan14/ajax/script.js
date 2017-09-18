var selectMhs = document.getElementById('idMhs');
var wadah = document.getElementById('wadah');

// event
selectMhs.addEventListener('change', function() {
	
	// bikin object ajax
	var xhr = new XMLHttpRequest();

	// cek kesiapan ajax dan sumber
	xhr.onreadystatechange = function() {
		if( xhr.readyState == 4 && xhr.status == 200 ) {
			wadah.innerHTML = xhr.responseText;
		}
	}

	xhr.open('get', 'tujuan.php?id=' + selectMhs.value, true);
	xhr.send();




});