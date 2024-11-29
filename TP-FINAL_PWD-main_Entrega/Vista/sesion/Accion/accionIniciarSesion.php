<?php
include_once("../../../configuracion.php");
$datos = data_submitted();
$objCaptcha = new ControlCaptcha();
$objSesion = new Session();
if ($objCaptcha->mtCaptcha($datos["mtcaptcha-verifiedtoken"])) {
    if ($objSesion->valida($datos)) {
        echo json_encode(array('success'=>1));
    } else {
        echo json_encode(array('success'=>0));
    }
} else {
    echo json_encode(array('success'=>-1));
}
?>