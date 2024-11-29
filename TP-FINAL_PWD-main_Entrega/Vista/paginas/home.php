<?php
include_once("../estructura/Cabecera.php");
?>


  <div class="container-fluid py-4">

      <div id="slider-home" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-indicators">
          <button class="active" data-bs-target="#slider-home" data-bs-slide-to="0"></button>
          <button data-bs-target="#slider-home" data-bs-slide-to="1"></button>
          <button data-bs-target="#slider-home" data-bs-slide-to="2"></button>
        </div>
        
        <div class="carousel-inner">

            <div class="carousel-item active">
              <img class="d-block w-100" src="./multimedia/imagen1.jpg" alt="">
            </div>

            <div class="carousel-item">
              <img class="d-block w-100" src="./multimedia/imagen2.jpg" alt="">
            </div>

            <div class="carousel-item">
              <img class="d-block w-100" src="./multimedia/imagen3.jpg" alt="">
            </div>

        </div>

          <button class="carousel-control-prev" data-bs-target="#slider-home" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>

          <button class="carousel-control-next" data-bs-target="#slider-home" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>

      </div>

  </div>

  <div class="container py-5">
    <h1 style="margin-top: 20px;" class="text-center mb-4">Los mas elegidos</h1>
    <p class="text-center text-muted">Explorá algunos de los productos más seleccionados por nuestros clientes.</p>
    <div class="card-container">
      <div class="card" style="width: 18rem;">
        <img src="https://images.samsung.com/is/image/samsung/p6pim/ar/sm-x200nzsmaro/gallery/ar-galaxy-a8-sm-x200-sm-x200nzsmaro-531066559?$720_576_PNG$" class="card-img-top" alt="Imagen 1">
        <div class="card-body text-center">
          <h5 class="card-title">Producto 1</h5>
          <p class="card-text">Esta es Producto que podras encontrar en nuestra tienda online</p>
          <a href="#" class="btn btn-primary">Ver más</a>
        </div>

      </div>
      <div class="card" style="width: 18rem;">
        <img src="https://images.samsung.com/is/image/samsung/p6pim/ar/sm-x200nzsmaro/gallery/ar-galaxy-a8-sm-x200-sm-x200nzsmaro-531066559?$720_576_PNG$" class="card-img-top" alt="Imagen 2">
        <div class="card-body text-center">
          <h5 class="card-title">Producto 2</h5>
          <p class="card-text">Producto que podras encontrar en nuestra tienda online</p>
          <a href="#" class="btn btn-primary">Ver más</a>
        </div>

      </div>
      <div class="card" style="width: 18rem;">  
        <img src="https://images.samsung.com/is/image/samsung/p6pim/ar/sm-x200nzsmaro/gallery/ar-galaxy-a8-sm-x200-sm-x200nzsmaro-531066559?$720_576_PNG$" class="card-img-top" alt="Imagen 3">
        <div class="card-body text-center">
          <h5 class="card-title">Producto 3</h5>
          <p class="card-text">Producto que podras encontrar en nuestra tienda online</p>
          <a href="#" class="btn btn-primary">Ver más</a>
        </div>
        
      </div>
    </div>
  </div>

<?php
include_once("../estructura/Pie.php")
?>