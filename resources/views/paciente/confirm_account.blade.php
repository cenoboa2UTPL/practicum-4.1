<!DOCTYPE html>
 
<html
  lang="en"
  class="light-style customizer-hide"
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

    <title>Create-Account</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{$this->asset('vendor/fonts/boxicons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{$this->asset('vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{$this->asset('vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{$this->asset('css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{$this->asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

    <link rel="stylesheet" href="{{$this->asset('css/estilos.css')}}">
    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{$this->asset('vendor/css/pages/page-auth.css')}}" />
    <link rel="icon" type="image/x-icon" href="{{$this->asset("img/avatars/anonimo_4.jpg")}}" />
    <!-- Helpers -->
    <script src="{{$this->asset('vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{$this->asset('js/config.js')}}"></script>
   
  </head>

  <body  style="background-color: #E6E6FA">
    <!-- Content -->

    <div class="container-fluid">
      
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-xl-4 col-lg-5 col-md-5 col-md-7">
                <div class="card">
                    <div class="card-header" style="background-color: aquamarine">
                        <div class="card-text">
                            <p class="h3 text-primary text-center letra">Confirmar la cuenta</p>
                            
                        </div>
                    </div>
                   <form action="{{$this->route("paciente/verify_account_paciente")}}" method="post">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                    <div class="card-body">
                       @if ($this->ExistSession('success_'))
                       <div class="alert alert-success">
                          {{$this->getSession('success_')}}
                       </div>
                       @endif
                      <div class="form-group">
                        <label for="usercodigo" class="form-label"><b>Ingrese el código <span class="text-danger">(*)</span></b></label>
                       <input type="text" class="form-control" id="usercodigo" name="usercodigo" placeholder="CÓDIGO..."
                       autofocus style="font-size: 20px">
                      </div>

                      <div class="mt-2">
                        @if ( $this->ExistSession("response"))
                            @if ($this->getSession("response") === 'error')
                                <span class="text-danger">
                                   Error, el código que escribiste no existe!.😒
                                </span>
                            @endif
                            @if ($this->getSession("response") === 'vacio')
                             <span class="text-danger"> Complete el campo código! 😢</span>
                            @endif
                        @php $this->destroySession("response") @endphp
                        @endif
                       
                      </div>
                      
                   </div>
 
                      <div class="mb-4 text-center">
                        <button class="btn_blue  col-6" type="submit" id="active_cuenta"><b>Activar mi cuenta<i class='bx bxs-user-account'></i></b> </button>
                      </div>
                   </form>
                </div>
            </div>
        </div>
    </div>

 
    <script src="{{$this->asset('vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{$this->asset('vendor/libs/popper/popper.js')}}"></script>
    <script src="{{$this->asset('vendor/js/bootstrap.js')}}"></script>
    <script src="{{$this->asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{$this->asset('vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{$this->asset('js/main.js')}}"></script>
    <script src="{{URL_BASE}}public/js/control.js"></script>
    <script>
   
    </script>
  </body>
</html>