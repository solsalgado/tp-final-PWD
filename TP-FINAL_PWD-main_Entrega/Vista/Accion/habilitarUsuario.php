<?php
include_once("../../configuracion.php");

$datos=data_submitted();
$objUsuario=new AbmUsuario();
$usuarioHabilitado=$objUsuario->habilitar($datos);
if($usuarioHabilitado){
    echo json_encode(array('success'=>1));
}else{
    echo json_encode(array('success'=>0));
}
?>