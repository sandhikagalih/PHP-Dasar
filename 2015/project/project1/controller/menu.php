<?php 

$page_id = ( isset( $_GET["page_id"] ) ) ? $_GET["page_id"] : "1";

require '../helper/functions.php';

require '../view/header.php'; 

tampil_menu($page_id);

require '../view/footer.php'; 

?>