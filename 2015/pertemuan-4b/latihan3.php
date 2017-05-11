<!doctype html>
<html>
<head>
	<title>Form Registrasi</title>
</head>
<body>
<form action="latihan3.php" method="post">
<table border="1" cellspacing="0" cellpadding="5" width="600" align="center">
	<tr>
		<td colspan="2" align="center">
			<h2>Form Registrasi</h2>
		</td>
	</tr>
	<tr>
		<td>Username : </td>
		<td><input type="text" name="username" size="26"></td>
	</tr>
	<tr>
		<td>Password :</td>
		<td><input type="password" name="password"> max 10 karakter</td>
	</tr>
	<tr>
		<td>Alamat e-mail :</td>
		<td><input type="text" name="email" size="32"></td>
	</tr>
	<tr>
		<td>Jenis Kelamin:</td>
		<td>
			<input type="radio" name="gender" value="Pria" checked> Pria
			<input type="radio" name="gender" value="Wanita"> Wanita
		</td>
	</tr>
	<tr>
		<td>Tanggal Lahir:</td>
		<td>
			tanggal 
			<select name="tanggal">
				<option value="1">1</option>
			</select>
			bulan
			<select name="bulan">
				<option value="Januari">Januari</option>
			</select>
			tahun
			<select name="tahun">
				<option value="2013">2013</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Asal Kota:</td>
		<td>
			<select name="kota">
				<option value="Bandung">Bandung</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Tentang Saya:</td>
		<td><textarea name="tentang_saya" id="tentang_saya" cols="60" rows="10"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" name="submit" value="Daftar">
		</td>
	</tr>
</table>
</form>

</body>
</html>