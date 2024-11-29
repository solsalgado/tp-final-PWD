<?php
include_once("../../configuracion.php");

$datos = data_submitted();
$objCompraEstado = new AbmCompraEstado();
$resultado = $objCompraEstado->realizarCompraCarrito($datos);

if ($resultado === 1) {
    echo json_encode(array('success' => 1));
} elseif ($resultado === 0) {
    echo json_encode(array('success' => 0));
} else {
    echo json_encode(array('success' => 2));
}
?>
