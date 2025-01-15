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
<div class="card">
    <div class="card-header" style="background-color: #4169E1">
        <h4 class="text-white">Completar mis datos</h4>
    </div>
    <div class="card-body">
        <div class="card-text mt-4">
            <h5 class="text-primary">Datos personales</h5>
        </div>
        <div class="row">
            {{-- Selección de tipo de documento --}}
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                <label for="tipo_doc">Tipo documento (*)</label>
                <select name="tipo_doc" id="tipo_doc" class="form-select">
                    @if (isset($TipoDocumentos))
                        @foreach ($TipoDocumentos as $tipo_doc)
                            <option value="{{$tipo_doc->id_tipo_doc}}">{{$tipo_doc->name_tipo_doc}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            {{-- Campo para ingresar el número de documento --}}
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                <label for="num_doc"># Documento (*)</label>
                <input type="text" name="num_doc" id="num_doc" class="form-control" placeholder="# documento..." autofocus>
            </div>
            {{-- Campo para apellidos y nombres --}}
            <div class="col-xl-5 col-lg-5 col-md-4 col-12">
                <label for="apell_nombre">Apellidos y nombres (*)</label>
                <input type="text" name="apell_nombre" id="apell_nombre" class="form-control" placeholder="Apellidos y nombres...">
            </div>
            {{-- Selección de género --}}
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                <label for="genero">Género (*)</label>
                <select name="genero" id="genero" class="form-select">
                    <option value="1">Masculino</option>
                    <option value="2">Femenino</option>
                </select>
            </div>
            {{-- Campo para la fecha de nacimiento --}}
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                <label for="fecha_nac">Fecha de nacimiento (*)</label>
                <input type="date" name="fecha_nac" id="fecha_nac" class="form-control" value="{{$this->addRestFecha("Y-m-d","-13100 day")}}">
            </div>
            {{-- Selección de departamento --}}
            <div class="col-xl-5 col-lg-5 col-md-5 col-12">
                <label for="depart">Departamento</label>
                <select name="depart" id="depart" class="form-select">
                @if (isset($Departamentos))
                    @foreach ($Departamentos as $dep)
                        <option value="{{$dep->id_departamento}}">{{strtoupper($dep->name_departamento)}}</option>
                    @endforeach
                @endif
                </select>
            </div>
            {{-- Selección de provincia (rellenado dinámico) --}}
            <div class="col-xl-5 col-lg-5 col-md-5 col-12">
                <label for="prov">Provincia</label>
                <select name="prov" id="prov" class="form-select"></select>
            </div>
            {{-- Selección de distrito (rellenado dinámico) --}}
            <div class="col-xl-7 col-lg-7 col-md-7 col-12">
                <label for="distrito">Distrito (*)</label>
                <select name="distrito" id="distrito" class="form-select"></select>
            </div>
            {{-- Campo para dirección --}}
            <div class="col-12">
                <label for="direccion">Dirección</label>
                <input name="direccion" id="direccion" class="form-control" placeholder="Ingrese su dirección...">
            </div>
        </div>

        {{-- Datos secundarios --}}
        <div class="card-text mt-3">
            <h5 class="text-primary">Datos secundarios</h5>
        </div>
        <div class="row">
            {{-- Teléfono --}}
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                <label for="telefono"># Teléfono (*)</label>
                <input name="telefono" id="telefono" class="form-control" placeholder="XXX XXX XXX">
            </div>
            {{-- Facebook --}}
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                <label for="facebook">Facebook</label>
                <input name="facebook" id="facebook" class="form-control" placeholder="Indicar su facebook...">
            </div>
            {{-- WhatsApp --}}
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                <label for="wasap">WhatsApp (*)</label>
                <input name="wasap" id="wasap" class="form-control" placeholder="# de WhatsApp...">
            </div>
            {{-- Estado civil --}}
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                <label for="estado_civil">Estado civíl (*)</label>
                <select name="estado_civil" id="estado_civil" class="form-select">
                 <option value="se">SIN ESPECIFICAR</option>
                 <option value="s">SOLTERO</option>
                 <option value="c">CASADO</option>
                 <option value="v">VIUDO</option>
                </select>
            </div>
            {{-- Apoderado --}}
            <div class="col-xl-12 col-lg-12 col-md-8 col-sm-12 col-12">
                <label for="apoderado">Apoderado </label>
                <input name="apoderado" id="apoderado" class="form-control">
            </div>
        </div>

        {{-- Botón para guardar los datos --}}
        <div class="text-end mt-4">
            <button class="btn-save" id="save_datos"> Guardar <i class='bx bxs-save'></i></button>
        </div>
    </div>
 </div>
@endsection

@section('js')
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>
  var RUTA = "{{URL_BASE}}"; // La URL base del sistema
  var TOKEN = "{{$this->Csrf_Token()}}"; // Token CSRF para seguridad

$(document).ready(function(){
    // Variables del formulario
    let Provincia = $('#prov'); let Distrito = $('#distrito'); let Departamento_ = $('#depart');
    let TipoDoc = $('#tipo_doc'); let NumDocumento = $('#num_doc'); let Persona = $('#apell_nombre');
    let Genero = $('#genero'); let FechaNacimiento = $('#fecha_nac'); let Direccion = $('#direccion');
    let Telefono = $('#telefono'); let Facebook = $('#facebook'); let Wasap = $('#wasap');
    let EstadoCivil = $('#estado_civil'); let Apoderado = $('#apoderado');

    // Mostrar provincias al seleccionar un departamento
    MostrarLasProvincias(Departamento_, Provincia);

    Departamento_.change(function(){
        MostrarLasProvincias($(this), Provincia);
    });

    // Mostrar distritos al seleccionar una provincia
    Provincia.change(function(){
        MostrarDistritos($(this), Distrito);
    });

    // Guardar datos al hacer clic en "Guardar"
    $('#save_datos').click(function(){
        if (NumDocumento.val().trim().length == 0) {
            NumDocumento.focus();
        } else if (Persona.val().trim().length == 0) {
            Persona.focus();
        } else if (Telefono.val().trim().length == 0) {
            Telefono.focus();
        } else if (Wasap.val().trim().length == 0) {
            Wasap.focus();
        } else {
            SaveCompleteDataPaciente(
                NumDocumento,
                Persona,
                Genero,
                Direccion,
                FechaNacimiento,
                TipoDoc,
                Distrito,
                Telefono,
                Facebook,
                Wasap,
                EstadoCivil,
                Apoderado
            );
        }
    });
});   

// Función para mostrar las provincias según el departamento seleccionado
var MostrarLasProvincias = (departamento, provincia) => {
    let option = '';
    $.ajax({
        url: RUTA + "provincia/mostrar?token_=" + TOKEN,
        method: "GET",
        data: { id_departamento: departamento.val() },
        success: function(response) {
            response = JSON.parse(response);

            response.response.forEach(provinciaData => {
                option += '<option value='+provinciaData.id_provincia+'>'+provinciaData.name_provincia.toUpperCase()+'</option>';
            });

            provincia.html(option);
            MostrarDistritos($('#prov'), $('#distrito'));
        }
    });
}

// Función para mostrar los distritos según la provincia seleccionada
var MostrarDistritos = (provincia, distrito_) => {
    let option = '';
    $.ajax({
        url: RUTA + "distritos/mostrar-para-la-provincia/" + provincia.val() + "?token_=" + TOKEN,
        method: "GET",
        success: function(response) {
            response = JSON.parse(response);

            response.response.forEach(distritoData => {
                option += '<option value='+distritoData.id_distrito+'>'+distritoData.name_distrito.toUpperCase()+'</option>';
            });

            distrito_.html(option);
        }
    });
}

// Función para guardar los datos completos del paciente
var SaveCompleteDataPaciente = function(
    documento_, persona_, genero_, direccion_, fecha_nac_, tipo_doc_, distrito_, telefono_,
    facebook_, wasap_, estado_civil_, apoderado_
) {
    $.ajax({
        url: RUTA + "paciente/completar_datos_",
        method: "POST",
        data: {
            token_: TOKEN,
            doc: documento_.val(),
            persona: persona_.val(),
            genero: genero_.val(),
            direccion: direccion_.val(),
            fecha_nac: fecha_nac_.val(),
            tipo_doc: tipo_doc_.val(),
            distrito: distrito_.val(),
            telefono: telefono_.val(),
            facebook: facebook_.val(),
            wasap: wasap_.val(),
            estado_civil: estado_civil_.val(),
            apoderado: apoderado_.val()
        },
        success: function(response) {
            response = JSON.parse(response);

            if (response.response === 'ok') {
                Swal.fire({
                    title: "¡Mensaje del sistema!",
                    text: "Sus datos han sido completados con éxito. Ahora puede solicitar una cita médica. 😁",
                    icon: "success",
                }).then(function() {
                    location.href = RUTA + "seleccionar-especialidad";
                });
            } else {
                // Manejo de errores
                Swal.fire({
                    title: "¡Mensaje del sistema!",
                    text: response.message || "Error al completar los datos del paciente.",
                    icon: "error",
                });
            }
        },
        error: function(err) {
            Swal.fire({
                title: "¡Mensaje del sistema!",
                text: "Error al completar los datos del paciente.",
                icon: "error",
            });
        }
    });
}
</script>
@endsection
