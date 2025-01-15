<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
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

    <title>login</title>

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
    <link rel="icon" type="image/x-icon" href="{{$this->asset("img/avatars/anonimo_7.png")}}" />
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
            
                    <div class="card-body">
                      <!-- /Logo -->
                      <h4 class="mb-2 text-center">Bienvenido nuevamente! 👋</h4>
                      <p class="mb-4 text-center">Ingrese sus credenciales correctas</p>

                      @if ($this->ExistSession("success_reset"))
                      <div class="text-center alert alert-success">
                      <p>{{$this->getSession("success_reset")}}</p>
                      </div>
                      @php $this->destroySession("success_reset") @endphp
                      @endif
                      
                      @if ($this->ExistSession("response"))
                      <div class="text-center alert alert-success">
                      <p>{{$this->getSession("response")}}</p>
                      </div>
                      @php $this->destroySession("response") @endphp
                      @endif
                      <form id="form_login" class="mb-3" action="{{$this->route('login/sigIn')}}" method="POST">
                        <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                        <div class="mb-3">
                          <label for="email" class="form-label"><b>Email or Username</b></label>
                          <input
                            type="text"
                            class="form-control"
                            id="email_username"
                            name="email_username"
                            placeholder="Escriba email o nombre de usuario..."
                            value="{{$this->old("email_username")}}"
                            autofocus
                          />
                        </div>
                       @if ($this->ExistSession("error_user"))
                        <b class="text-danger mx-1"> {{$this->getSession("error_user")}} <i class='bx bxs-sad'></i></b>
                        @php $this->destroySession("error_user") @endphp
                        @endif
                        <div class="mb-3 form-password-toggle">
                          <div class="d-flex justify-content-between">
                            <label class="form-label" for="password"><b>Password</b></label>
                            <a href="{{$this->route('reset-password')}}">
                              <small>Olvidaste tu contraseña?</small>
                            </a>
                          </div>
                          <div class="input-group input-group-merge">
                            <input
                              type="password"
                              id="password"
                              class="form-control"
                              name="password"
                              placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                              value="{{$this->old("password")}}"
                              aria-describedby="password"
                            />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                          </div>
                          @if ($this->ExistSession("error_pas"))
                            <b class="text-danger mx-1"> {{$this->getSession("error_pas")}} <i class='bx bxs-sad'></i></b>
                          @php $this->destroySession("error_pas") @endphp
                          @endif
                        </div>
                        <div class="mb-3">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me" />
                            <label class="form-check-label" for="remember_me" style="cursor: pointer"> Recordar mi sesión </label>
                          </div>
                        </div>
                        <div class="mb-3">
                          <button class="btn_twiter  d-grid w-100 p-2" type="submit" id="login"><b>Entrar <i class='bx bx-log-in'></i></b> </button>
                        </div>
                      </form>
        
                      <p class="text-center">
                        <span>¿Eres paciente nuevo ❓❓</span>
                        <a href="{{$this->route('create_account_paciente')}}">
                          <b>Regístrate <i class='bx bx-right-arrow-alt'></i></b>
                        </a>
                      </p>
                    </div>
                    
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
      $(document).ready(function(){
        let Username = $('#email_username');
        let Pasword = $('#password');
        enter('email_username','password');

        $('#login').click(function(evento){
          evento.preventDefault();

          if($('#email_username').val().trim().length == 0)
          {
            $('#email_username').focus();
          }
          else
          {
            if($('#password').val().trim().length == 0)
            {
              $('#password').focus();
            }
            else
            {
              $('#form_login').submit();
            }
          }


          
        });
      });
    </script>
  </body>
</html>