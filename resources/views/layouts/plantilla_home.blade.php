<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('title_home')</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="public/assets/img/favicon.png" rel="icon">
  <link href="public/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Vendor CSS Files -->
  <link href="{{URL_BASE}}public/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="{{URL_BASE}}public/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{URL_BASE}}public/assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Medilab
  * Updated: Mar 10 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
 <!-- ======= Top Bar ======= -->
 <div id="topbar" class="d-flex align-items-center fixed-top">
    <div class="container d-flex justify-content-between">

      <div class="d-none d-lg-flex social-links align-items-center">
        <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
        <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></i></a>
      </div>
    </div>
  </div>
  
 @yield('contenido')   
  
  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{URL_BASE}}public/assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="{{URL_BASE}}public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{URL_BASE}}public/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="{{URL_BASE}}public/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="{{URL_BASE}}public/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
 

</body>

</html>