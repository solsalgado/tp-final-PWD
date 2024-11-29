<?php
include_once("../../configuracion.php");

$datos=data_submitted();
$objMenu=new AbmMenu();
$menuHabilitado=$objMenu->habilitar($datos);
if($menuHabilitado){
    echo json_encode(array('success'=>1));
}else{
    echo json_encode(array('success'=>0));
}
?>