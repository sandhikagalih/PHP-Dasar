var container = document.getElementById('container');
var cari = document.getElementById('cari');
var keyword = document.getElementById('keyword');


cari.style.display = 'none';

keyword.addEventListener('keyup', function() {
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if( xhr.readyState == 4 && xhr.status == 200 ) {
			container.innerHTML = xhr.responseText;
		}
	}
	xhr.open('get', 'cari.php?keyword=' + keyword.value, true);
	xhr.send();
});