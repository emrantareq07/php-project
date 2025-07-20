<!-- ======= Hero Section ======= -->
<section id="" class="position-relative">

  <!-- Internal CSS -->
  <style>
    .carousel-inner img {
      height: 500px;
      object-fit: cover;
    }

    .carousel-overlay {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(0, 0, 0, 0.5); /* ✅ Dark transparent overlay */
      z-index: 1;
    }

    .carousel-text-content {
      position: absolute;
      top: 50%;
      left: 0;
      transform: translateY(-50%);
      padding-left: 3rem;
      z-index: 2;
      color: white;
    }
  </style>

  <!-- Carousel -->
  <div id="demo" class="carousel slide my-2" data-bs-ride="carousel" data-aos="zoom-in" data-aos-delay="100" style="position: relative;">

    <!-- Indicators/dots -->
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
    </div>

    <!-- The slideshow/carousel -->
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="assets/img/1.jpg" class="d-block w-100">
      </div>
      <div class="carousel-item">
        <img src="assets/img/2.jpg" class="d-block w-100">
      </div>
      <div class="carousel-item">
        <img src="assets/img/3.jpg" class="d-block w-100">
      </div>
    </div>

    <!-- ✅ Overlay Layer -->
    <div class="carousel-overlay"></div>

    <!-- ✅ Text Over All Images -->
    <div class="carousel-text-content">
      <h1 class="shadow-sm">New Morning Glory School</h1>
      <h3 class="text-info">Learning Today,<br>Leading Tomorrow</h3>
      <h2>(Play Group to Class Five)</h2>
      <h4 class="text-warning">Address: 44/I/4, Indira Road, East Rajabazar, Tejgaon, Dhaka-1215.</h4>
      <h4 class="text-warning">Contact:</h4>
      <a href="courses.html" class="btn btn-primary mt-2">Get Started</a>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</section>




