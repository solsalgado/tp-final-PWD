<?php
include_once("../../configuracion.php");

$datos = data_submitted();
$objPersona = new AbmUsuario();
$objCaptcha = new ControlCaptcha();
if ($objCaptcha->reCaptchav2($datos["g-recaptcha-response"])) {
    if ($objPersona->alta($datos)) {
        echo json_encode(array('success'=>1));
    } else {
        echo json_encode(array('success'=>0));
    }
}else{
    echo json_encode(array('success'=>-1));
}
?>