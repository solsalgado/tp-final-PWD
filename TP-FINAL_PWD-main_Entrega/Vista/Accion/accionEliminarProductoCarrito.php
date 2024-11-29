<?php
include_once("../../configuracion.php");

$datos = data_submitted();
$objCompraItem = new AbmCompraItem();
$objCompraItem->eliminarProductoCarrito($datos);