<?php 
require 'functions.php';

$id = $_GET["id"];
$query = "SELECT * FROM mahasiswa WHERE id = $id";
$mahasiswa = query($query);
?>

<?php foreach( $mahasiswa as $row ) : ?>
	<ul>
		<li><?php echo $row["nama"]; ?></li>
		<li><?php echo $row["email"]; ?></li>
	</ul>
<?php endforeach; ?>