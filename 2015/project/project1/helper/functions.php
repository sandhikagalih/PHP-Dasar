<?php 

function generate_link($page_id) {

	$menu = simplexml_load_file("../model/menu.xml");

	foreach ( $menu->xpath("//menu/kategori") as $kategori ) : ?>

		<li class="<?= cek_active($kategori["id"],$page_id); ?>"><a href="menu.php?page_id=<?= $kategori["id"]; ?>"><?= $kategori["nama"]; ?></a></li>

	<?php endforeach;
}



function tampil_menu($page_id) {
 	
 	$menu = simplexml_load_file("../model/menu.xml");

 	foreach ( $menu->xpath("//menu/kategori[@id = '$page_id']") as $kategori ) : 
 		$kat = $kategori["nama"];
 	?>

		<div class="menu">

			<?php 
				
				if ($kategori->subkategori) {
					echo menu_dengan_subkategori($menu);
				} else { 
					echo menu_tanpa_subkategori($menu,$kat);
				}

			?>

		</div>

	<?php endforeach; 
}



function menu_tanpa_subkategori($menu,$kat) { ?>
	<h2><?= $kat; ?></h2>
	<table class="rounded striped">
	<?php 
		foreach ($menu->xpath("//kategori[@nama = '$kat']/item") as $nama) :	 
	?>
		<tr>
			<td><h3><?= $nama["nama"]; ?></h3></td>
			<td class="harga"><h3><?= $nama->harga; ?></h3></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php }



function menu_dengan_subkategori($menu) {
	foreach ( $menu->xpath("//subkategori") as $subkategori ) :
		$subkat = $subkategori["nama"]; ?>

		<h2><?= $subkat; ?></h2>
		<table class="rounded striped">
		<?php 
			foreach ( $menu->xpath("//subkategori[@nama = '$subkat']/item" ) as $nama) :	 
		?>
			<tr>
				<td><h3><?= $nama["nama"]; ?></h3></td>
				<td class="harga"><h3><?= $nama->harga; ?></h3></td>
			</tr>
		<?php endforeach; ?>
		</table>

	<?php endforeach;
}



function cek_active($id,$page_id) {
		return ( ( $id == $page_id ) ? "active" : "" );
}
 
?>