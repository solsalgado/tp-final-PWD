<?php
include "../../configuracion.php";
$objSession = new Session();
$menues = [];
if ($objSession->activa()) {
  $idRoles=$objSession->getRol();
  $objMenuRol = new AbmMenuRol();
  $objRol = new AbmRol();
  $menues = $objMenuRol->menuesByIdRol($objSession->getVista());
  $objRoles = $objRol->obtenerObj($idRoles);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabajo Practico Final PWD</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/producto.css">
  <link rel="stylesheet" href="../css/home.css">
  <link rel="stylesheet" href="../alertas/dist/sweetalert2.min.css">
  <script src="../bootstrap/js/bootstrap.min.js"></script>
  <script src="../alertas/dist/sweetalert2.all.min.js"></script>
  <script src="../jQuery/jquery-3.6.1.min.js"></script>
  <script src="../js/cerrarSesion.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="../js/cambiarVista.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  
  <header class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container-fluid">
    <a href="#" class="enlace-tecnologia">
    <i class="fas fa-laptop"></i> 
</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExample03">
        <ul class="navbar-nav me-auto mb-2 m-2 mb-sm-0">
          <li><a href="../../index.php" role="button" class="px-2 mx-1 btn btn-lg btn-outline-dark" style="border-color: white; color: white;">Home</a></li>
          <?php
          foreach ($menues as $objMenu) {
            if ($objMenu->getMeDeshabilitado() == NULL) {
          ?>
              <li><a href='<?php echo $objMenu->getMeDescripcion() ?>' role="button" class="px-2 mx-1 btn btn-lg btn-outline-dark" style="border-color: white; color: white;"><?php echo $objMenu->getMeNombre() ?></a></li>
          <?php
            }
          }
          ?>
        </ul>


        <div class="text-end d-flex align-items-center">
          <?php if ($objSession->activa()) {
            if (count($objRoles) > 1) {
          ?>
              <select class="form-select  me-2" id="cambiar_vista" aria-label=".form-select-lg example" >
                <option selected disabled><?php echo $_SESSION['vista']->getRolDescripcion() ?></option>
                <?php
                foreach ($objRoles as $objRol) {
                ?>
                  <option value="<?php echo $objRol->getIdRol() ?>"><?php echo $objRol->getRolDescripcion() ?></option>
              <?php
                }
              }
              ?>
              </select>
              <button id="log" type='button' class='btn btn-lg btn-outline-dark me-2' onclick="cerrarSesion()" style="border-color: white; color: white;">SALIR</button>
            <?php
          } else {
            ?>
              <a href='../sesion/IniciarSesion.php' class='btn btn-lg btn-outline-dark me-2' style="border-color: white; color: white;">INGRESAR</a>
            <?php
          } ?>
        </div>
      </div>
    </div>
  </header>
  