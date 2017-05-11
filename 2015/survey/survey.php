<?php 
session_start();

// cek apabila user mencoba mengakses langsung halaman ini
if ( !isset($_SESSION["nrp"]) ) {
    header("Location: login.php");
}

require 'helpers/functions.php';
$conn = konek();

$nrp = $_SESSION["nrp"];
$userid = query($conn, "SELECT id FROM users WHERE nrp = '$nrp'");

$pertanyaan = query($conn, "SELECT * FROM questions ORDER BY id ASC");
$jmlPertanyaan = count($pertanyaan);

$pilihan = query($conn, "SELECT * FROM options ORDER BY id ASC");
$i = 1;

?>
<!doctype html>
<head>
	<title>Survey</title>
	<link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
	<!-- Google+ Metadata /-->
	<meta itemprop="name" content="">
	<meta itemprop="description" content="">
	<meta itemprop="image" content="">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

	<!-- We highly recommend you use SASS and write your custom styles in sass/_custom.scss.
		 However, there is a blank style.css in the css directory should you prefer -->
	<link rel="stylesheet" href="css/gumby.css">
	<link rel="stylesheet" href="css/style.css">

	<script src="js/libs/modernizr-2.6.2.min.js"></script>
</head>

<body>


<div class="container">
	<div class="row">
		<div class="centered nine columns content">
			<input type="hidden" id="jmlPertanyaan" value="<?= $jmlPertanyaan; ?>">
			<?php foreach( $pertanyaan as $baris ) : ?>
				<dl>
					<input type="hidden" id="userid" value="<?= $userid["id"]; ?>">
					<input type="hidden" id="questionid-<?= $i; ?>" value="<?= $baris["id"]; ?>">
					<dt>
						<?= $i . '.'; ?> <?= $baris["question"]; ?>
					</dt>
					<dd>
						<?php foreach( $pilihan as $opsi ) : ?>
							<li class="field">
								<label class="radio checked" for="opsi<?= $opsi["id"]; ?>">
								    <input type="radio" name="opsi-pertanyaan-<?= $i; ?>" id="opsi-pertanyaan" value="<?= $opsi["id"]; ?>" >
								    <span></span> <?= $opsi["option"]; ?>
							  	</label>
							</li>
						<?php endforeach; ?>
					</dd>
					<div class="medium btn secondary tombol-next"><a href="#">Next</a></div>
				</dl>
				<?php $i++; ?>
			<?php endforeach; ?>
	
		</div>
	</div>
</div>

<div class="modal" id="modalbox">
  <div class="content">
    <a class="close switch" gumby-trigger="|#modalbox"><i class="icon-cancel" /></i></a>
    <div class="row">
      <div class="ten columns centered text-center">
        <h2>Hasil ajax.</h2>
        <div class="hasil"></div>
      <!-- <p class="btn primary medium">
          <a href="#" class="switch" gumby-trigger="|#modalbox">Close Modal</a>
        </p> -->
      </div>
    </div>
  </div>
</div>


<script src="js/jquery/jquery-2.0.3.js"></script>

<!--
Include gumby.js followed by UI modules followed by gumby.init.js
Or concatenate and minify into a single file -->
<script gumby-touch="js/libs" src="js/libs/gumby.js"></script>
<script src="js/libs/ui/gumby.retina.js"></script>
<script src="js/libs/ui/gumby.fixed.js"></script>
<script src="js/libs/ui/gumby.skiplink.js"></script>
<script src="js/libs/ui/gumby.toggleswitch.js"></script>
<script src="js/libs/ui/gumby.checkbox.js"></script>
<script src="js/libs/ui/gumby.radiobtn.js"></script>
<script src="js/libs/ui/gumby.tabs.js"></script>
<script src="js/libs/ui/gumby.navbar.js"></script>
<script src="js/libs/ui/jquery.validation.js"></script>
<script src="js/libs/gumby.init.js"></script>

<!--
gumby.min.js contains gumby.js, all UI modules and gumby.init.js
<script src="js/libs/gumby.min.js"></script> -->
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>
<script src="js/script.js"></script>
 </body>
</html>
