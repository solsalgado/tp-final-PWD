<?php
include_once("../../configuracion.php");

$datos = data_submitted();
$objCarrito = new AbmCarrito();

echo $objCarrito->cargarProductoCarrito($datos);
?>
