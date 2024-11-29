<?php
include_once("../../configuracion.php");

$datos = data_submitted();
$objCompraEstado = new AbmCompraEstado();
$resultado = $objCompraEstado->obtenerHistorial($datos);

if ($resultado !== null) {
    echo json_encode(array('success' => $resultado));
} else {
    echo json_encode(array('success' => 0));
}
