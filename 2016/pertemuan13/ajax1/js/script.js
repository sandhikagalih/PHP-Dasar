// ambil elemen input
var cari = document.getElementById('cari');
// buat event, ketika user mengetikkan keyword
cari.onkeyup = function() {

	var xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function() {
		if( xhr.readyState == 4 && xhr.status == 200 ) {
			var container = document.querySelector('.container');
			container.innerHTML = xhr.responseText;
		}
	}

	xhr.open('GET', 'helpers/cari.php?cari=' + cari.value, true);
	xhr.send();

}