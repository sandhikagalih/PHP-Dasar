var container = document.getElementById('container');
var cari = document.getElementById('cari');
var keyword = document.getElementById('keyword');
var spinner = document.getElementById('spinner');

cari.style.display = 'none';

keyword.addEventListener('keyup', function() {
	var xhr = new XMLHttpRequest();
	spinner.style.display = 'block';
	xhr.onreadystatechange = function() {
		if( xhr.readyState == 4 && xhr.status == 200 ) {
			container.innerHTML = xhr.responseText;
			spinner.style.display = 'none';
		}
	}
	xhr.open('get', 'cari.php?keyword=' + keyword.value, true);
	xhr.send();
});