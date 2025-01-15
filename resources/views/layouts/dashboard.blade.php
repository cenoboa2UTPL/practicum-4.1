<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>@yield('title_dashboard')</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{$this->asset('img/favicon/utpl.png')}}" />


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    {{--- CSS PARA DATA TABLES---}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{$this->asset("vendor/fonts/boxicons.css")}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{$this->asset("vendor/css/core.css")}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{$this->asset("vendor/css/theme-default.css")}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{$this->asset("css/demo.css")}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{$this->asset("vendor/libs/perfect-scrollbar/perfect-scrollbar.css")}}" />

    <link rel="stylesheet" href="{{$this->asset("vendor/libs/apex-charts/apex-charts.css")}}" />

    <!-- Page CSS -->
    @yield('css')
    <!-- Helpers -->
    <script src="{{$this->asset("vendor/js/helpers.js")}}"></script>
   {{--- CSS SWEET ALERT 2---}}

  
   <link rel="stylesheet" href="{{$this->NodeModule("sweetalert2/dist/sweetalert2.css")}}">
  
   <link rel="stylesheet" href="{{$this->NodeModule("sweetalert2/dist/sweetalert2.min.css")}}">
  
  
   <link rel="stylesheet" href="{{$this->asset("css/estilos.css")}}">
   <link rel="stylesheet" href="{{$this->asset("css/jquery.loadingModal.css")}}">

   {{---TIPO DE LETRA ---}}
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Asap+Condensed:wght@200;300&family=Lato:wght@300&family=Oswald:wght@200&family=Pathway+Extreme&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{$this->asset("js/config.js")}}"></script>

  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar @yield('expandir')" >
      <div class="layout-container"  id="body_">
        <!-- Menu aside -->
        
         @if (isset($this->profile()->rol))
         @include($this->getComponents("dashboard.aside"))
      
         <!-- / Menu -->
 
         <!-- Layout container -->
         <div class="layout-page">
           <!-- Navbar -->
           @include($this->getComponents("dashboard.nav"))
         
         @endif

          <!-- / Navbar wrpper -->
          @include($this->getComponents("dashboard.wrapper"))
        
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
 

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{$this->asset("vendor/libs/jquery/jquery.js")}}"></script>
    <script src="{{$this->asset("vendor/libs/popper/popper.js")}}"></script>
    <script src="{{$this->asset("vendor/js/bootstrap.js")}}"></script>
    <script src="{{$this->asset("vendor/libs/perfect-scrollbar/perfect-scrollbar.js")}}"></script>

    <script src="{{$this->asset("vendor/js/menu.js")}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{$this->asset("vendor/libs/apex-charts/apexcharts.js")}}"></script>

    <!-- Main JS -->
    <script src="{{$this->asset("js/main.js")}}"></script>

    <!-- Page JS -->
    <script src="{{$this->asset("js/dashboards-analytics.js")}}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    {{--- JS PARA DATA TABLES----}}
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>

    {{--- JS PARA SWEET ALERT 2-----}}
    <script src="{{$this->NodeModule("sweetalert2/dist/sweetalert2.all.js")}}"></script>
    <script src="{{$this->NodeModule("sweetalert2/dist/sweetalert2.all.min.js")}}"></script>
    <script src="{{$this->NodeModule("sweetalert2/dist/sweetalert2.js")}}"></script>
    <script src="{{$this->NodeModule("sweetalert2/dist/sweetalert2.min.js")}}"></script>

    {{---- AXIOS ----}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{$this->asset('js/ui-popover.js')}}"></script>
    <script src="{{$this->asset('js/ui-toasts.js')}}"></script>
    <script src="{{$this->asset('js/jquery.loadingModal.js')}}"></script>
    
    @yield('js')
  </body>
</html>
