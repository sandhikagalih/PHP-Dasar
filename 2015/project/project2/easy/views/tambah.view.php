<?php require '../views/header.php'; ?>

<div class="small info pill-left btn icon-left entypo icon-left-open"><a href="main.php">Back</a></div>

<h1>Tambah Data Mahasiswa</h1>

<div class="form-mhs">
	<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">

	<ul>
		<li class="prepend field">
			<span class="adjoined"><i class="icon-user"></i></span>
	    	<input class="wide text input" type="text" name="nama" placeholder="Nama" required autocomplete="off" />
	  	</li>
	  	<li class="prepend field">
			<span class="adjoined"><i class="icon-graduation-cap"></i></span>
	    	<input class="wide text input" type="text" name="universitas" placeholder="Universitas" required autocomplete="off" />
	  	</li>
	  	<li class="prepend field">
			<span class="adjoined"><i class="icon-location"></i></span>
	    	<input class="wide text input" type="text" name="kota" placeholder="Kota" required autocomplete="off" />
	  	</li>
	  	<li class="field">
	  		<h5>Pilih Fakultas:</h5>
		  <div class="picker">
		  	<select name="fakultas" id="fakultas">
		  		<option value="#" disabled>Pilih Fakultas:</option>
				<?php foreach($fakultas as $row) : ?>
					<option value="<?= $row["id"] ?>"><?= $row["nama"] ?></option>
				<?php endforeach; ?>
		    </select>
		  </div>
		</li>
	  	<li class="field">
	  		<h5>Pilih Jurusan:</h5>
		  <div class="picker">
		    <select name="jurusan" id="jurusan">
		      	<option value="#" disabled>Fakultas Teknik:</option>
				<?php foreach($jurusan as $row) : ?>
		      		<option value="<?= $row["id"] ?>"><?= $row["nama"] ?></option>
		      	<?php endforeach;  ?>
		    </select>
		  </div>
		</li>
		<div class="medium default pretty btn"><input type="submit" value="Submit" name="submit" /></div>
	</ul>

	</form>
</div>

<?php require '../views/footer.php'; ?>