<?php require 'header.php'; ?>

<div class="small info pill-left btn icon-left entypo icon-left-open"><a href="main.php">Back</a></div>

<h1>Ubah Data Mahasiswa</h1>

<div class="form-mhs">
	<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?= $id; ?>">
	<ul>
		<li>
			<img src="../images/foto/<?= $hasil["foto"]; ?>" width="80">
		</li>
		<li class="field">
			<!-- zaini -->
			<div class="medium primary btn icon-left entypo icon-camera fileinput-button">
		        <i class="glyphicon glyphicon-plus"></i>
		        <a href="#">Ganti Foto...</a>
		        <!-- The file input field used as target for the file upload widget -->
		        <input id="fileupload" type="file" name="fupload">
		        <div id="files" class="files"></div>
		    </div>
			<!-- <input type="file" class="wide" name="fupload" id="fupload"/> --> 
			<!-- zaini -->
	  	</li>
		<li class="prepend field">
			<span class="adjoined"><i class="icon-user"></i></span>
	    	<input class="wide text input" type="text" name="nama" placeholder="Nama" required value="<?= $hasil["nama"]; ?>" autocomplete="off" />
	  	</li>
	  	<li class="prepend field">
			<span class="adjoined"><i class="icon-graduation-cap"></i></span>
	    	<input class="wide text input" type="text" name="universitas" placeholder="Universitas" required value="<?= $hasil["universitas"]; ?>" autocomplete="off" />
	  	</li>
	  	<li class="prepend field">
			<span class="adjoined"><i class="icon-location"></i></span>
	    	<input class="wide text input" type="text" name="kota" placeholder="Kota" required value="<?= $hasil["kota"]; ?>" autocomplete="off" />
	  	</li>
	  	<li class="field">
	  		<h5>Pilih Fakultas:</h5>
		  <div class="picker">
		  	<select name="fakultas" id="fakultas">
		  		<option value="#" disabled>Pilih Fakultas:</option>
				<?php foreach($fakultas as $row) : ?>
					<?php if( $row["id"]  == $hasil["id"]) : ?>
						<option value="<?= $row["id"] ?>" selected><?= $row["nama"] ?></option>
					<?php else : ?>
						<option value="<?= $row["id"] ?>"><?= $row["nama"] ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
		    </select>
		  </div>
		</li>
	  	<li class="field">
	  		<h5>Pilih Jurusan:</h5>
		  <div class="picker">
		    <select name="jurusan" id="jurusan">
		      	<option value="#" disabled>Pilih Jurusan:</option>
				<?php foreach($jurusan as $row) : ?>
		      		<option value="<?= $row["id"] ?>" <?php cek($hasil["jurusan"], $row["id"]); ?>><?= $row["nama"] ?></option>
		      	<?php endforeach;  ?>
		    </select>
		  </div>
		</li>
		<div class="medium default pretty btn"><input type="submit" value="Ubah Data" name="submit" /></div>
	</ul>

	</form>
</div>

<?php require 'footer.php'; ?>