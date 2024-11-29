<?php
include_once("../../configuracion.php");

$datos = data_submitted();
$objCompraItem = new AbmCompraItem();
$resultado = $objCompraItem->obtenerProductosMisCompras($datos);

if ($resultado !== null) {
    echo json_encode(array('success' => $resultado));
} else {
    echo json_encode(array('success' => 0));
}
