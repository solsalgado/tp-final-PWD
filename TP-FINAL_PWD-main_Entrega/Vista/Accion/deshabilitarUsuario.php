<?php
include_once("../../configuracion.php");

$datos=data_submitted();
$objUsuario=new AbmUsuario();
$usuarioDeshabilitado=$objUsuario->deshabilitar($datos);
if($usuarioDeshabilitado){
    echo json_encode(array('success'=>1));
}else{
    echo json_encode(array('success'=>0));
}
?>