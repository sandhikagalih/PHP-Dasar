		var xhr = null;

		if( window.XMLHttpRequest ) {
			xhr = new XMLHttpRequest();
		} else {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}

		if( xhr == null ) {
			alert("browser tidak mendukung ajax!");
			return;
		}

		xhr.onreadystatechange = function() {
			if( (xhr.readyState == 4) && (xhr.status == 200) ) {
				document.getElementById('elemen').innerHTML = xhr.responseText;
			}
		}

		xhr.open("method", "url", true);
		// xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xhr.send();