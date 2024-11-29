<?php
include_once("../../configuracion.php");

$datos = data_submitted();
$objCompraEstado = new AbmCompraEstado();
if ($objCompraEstado->modificarEstados($datos)) {
    echo json_encode(array('success' => 1));
} else {
    echo json_encode(array('success' => 0));
}
