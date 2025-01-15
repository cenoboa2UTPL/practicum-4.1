@extends($this->Layouts("dashboard"))

{{-- Se extiende la plantilla base del dashboard --}}

@section('title_dashboard','Gestión de usuarios')
{{-- Título de la página que se mostrará en el navegador --}}

@section('css')
    {{-- Definición de estilos específicos para esta página --}}
    <style>
      #tabla-usuarios>thead>tr>th {
        background-color: #4169E1; /* Color de fondo de los encabezados */
        color: aliceblue; /* Color del texto */
      }
    </style>
    <link rel="stylesheet" href="{{$this->asset("css/estilos.css")}}">
    {{-- Enlace a un archivo CSS externo específico --}}
@endsection

@section('contenido')
{{-- Contenido principal de la vista --}}
<div class="mx-3">
  <div class="col-12">
    <div class="nav-align-top mb-4">
      {{-- Navegación con pestañas para gestionar usuarios y cuentas --}}
      <ul class="nav nav-tabs nav-fill" role="tablist" id="tab_user_">
        <li class="nav-item">
          <button
            type="button"
            class="nav-link active"
            role="tab"
            data-bs-toggle="tab"
            data-bs-target="#navs-justified-home"
            aria-controls="navs-justified-home"
            aria-selected="true"
            style="color: #4169E1"
            id="gestion_user"
          >
            <i class="tf-icons bx bx-home"></i> Gestionar usuarios
          </button>
        </li>
        
        <li class="nav-item">
          <button
            type="button"
            class="nav-link"
            role="tab"
            data-bs-toggle="tab"
            data-bs-target="#navs-justified-messages"
            aria-controls="navs-justified-messages"
            aria-selected="false"
            style="color:#48D1CC"
          >
            <i class="tf-icons bx bx-message-square"></i> Cuenta de usuario pacientes
          </button>
        </li>
      </ul>
      <div class="tab-content">
        {{-- Contenido de la primera pestaña: Gestión de usuarios --}}
        <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
            {{-- Botón para abrir el modal de creación de usuario --}}
            <button class="btn_3d col-xl-3 col-lg-4 col-md-5 col-sm-5 col-12" id="modal-create-user">
              <b class="letra"> Agregar uno nuevo<i class='bx bxs-message-rounded-add'></i></b>
            </button>
            <br>
            <div class="card-text">
              {{-- Tabla de usuarios, los datos se llenan dinámicamente con DataTables --}}
              <div class="table-responsive">
                <table class="table table-bordered table-striped nowrap responsive" id="tabla-usuarios" style="width: 100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>PERSONA</th>
                      <th>NOMBRE DE USUARIO</th>
                      <th>GÉNERO</th>
                      <th>EMAIL</th>
                      <th>ROL</th>
                      <th>FOTO</th>
                      <th>ESTADO</th>
                      <th>GESTIONAR</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
        </div>
        
        {{-- Contenido de la pestaña de cuentas de usuario de pacientes --}}
        <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
          <div class="card-text">
            <div class="table-responsive">
              <table class="table table-bordered table-striped nowrap" id="tabla-usuarios-cuenta" style="width: 100%">
                <thead style="background-color: #7994e2">
                  <tr>
                    <th class="text-white">#</th>
                    <th class="text-white"># DOCUMENTO</th>
                    <th class="text-white">PERSONA</th>
                    <th class="text-white">ACCIÓN</th>
                  </tr>
                </thead>
              </table>
            </div>      
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal para crear o editar usuarios --}}
<div class="modal fade" id="modal_user">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background: #4169E1">
        <h4 class="text-white"><span id="text_modal_user">Crear usuarios</span> <b><i class='bx bx-user-plus'></i></b></h4>
        <button type="button" class="btn-close close_user" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="form_users_create">
          {{-- Campos para los datos del usuario (nombre, email, rol, etc.) --}}
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="rol" class="form-label"><b>Rol (*)</b></label>
                <select name="rol" id="rol" class="form-select border-primary">
                  <option value="Director">Director</option>
                  <option value="Admisión">Admisión</option>
                  <option value="Enfermera-Triaje">Enfermera-Triaje</option>
                </select>
              </div>
            </div>

            {{-- Más campos adicionales --}}
          </div>
        </form>
      </div>

      <div class="modal-footer">
        {{-- Botones para guardar o actualizar --}}
        <button class="button-store" id="btn_save"><b>Guardar</b> <i class='bx bx-save'></i></button>
        <button class="button-store" id="btn_update" style="display: none"><b>Guardar cambios</b> <i class='bx bx-save'></i></button>
      </div>
    </div>
  </div>
</div>

@section('js')
<script>
  // Variables globales
  var URL = "{{URL_BASE}}";
  var TOKEN = "{{$this->Csrf_Token()}}";

  $(document).ready(function(){
    // Evento para abrir el modal de creación
    $('#modal-create-user').click(function(){
      $('#modal_user').modal('show');
    });

    // Configuración de DataTables
    $('#tabla-usuarios').DataTable({
      ajax: {
        url: URL + "user_gestion_mostrar?token_=" + TOKEN,
        method: "GET",
        dataSrc: "usuarios"
      },
      columns: [
        { data: "nombres" },
        // Otras columnas...
      ]
    });
  });
</script>
@endsection
