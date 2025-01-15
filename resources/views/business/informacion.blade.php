@extends($this->Layouts("dashboard"))

@section("title_dashboard","Configurar datos empresa")

@section('css')
    <style>
        #hora_inicio_atencion,#hora_cierre_atencion
        {
            color:crimson;
            border: 1px solid #3ad5ec;
        }
        #tabla_hora_atencion_>thead>tr>th
        {
            background-color: #4169E1;
            color: antiquewhite;
        }
     td.hide_me
        {
        display: none;
        }
    </style>
@endsection

@section('contenido')
    <div class="col-12" id="car">
        <div class="nav-align-top mb-4">
          <ul class="nav nav-tabs nav-fill" role="tablist">
            
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
                >
                <i class='bx bxs-data'></i> <b>Copia y restauración del sistema</b>
            
                </button>
              </li>
            
            @if (isset($this->profile()->rol))
            <li class="nav-item">
              <button
                type="button"
                class="nav-link"
                role="tab"
                data-bs-toggle="tab"
                data-bs-target="#navs-justified-profile"
                aria-controls="navs-justified-profile"
                aria-selected="false"
                style="color:#FF4500"
              >
              <i class='bx bx-calendar'></i> Horarios atención
              </button>
            </li>
            @endif
            @if (isset($this->profile()->rol))
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
              <i class='bx bxs-calendar-week'></i> Días Laborables
              </button>
            </li>
            @endif
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                <div class="form-group">
                    @if ($this->ExistSession("mensaje_error"))
                        <div class="alert alert-danger">
                            {{$this->getSession("mensaje_error")}}
                        </div>
                        {{$this->destroySession("mensaje_error")}}
                    @endif

                    
                    <label for="form-group"><b>Que desea realizar ?</b></label>
                    <select name="opcion_sistema" id="opcion_sistema" class="form-select">
                        
                        @if (isset($this->profile()->rol))
                        <option value="1">Copia de seguridad</option>
                        @endif
                        <option value="2">Restaurar sistema</option>
                    </select>
                    <br>
                    <img src="{{$this->asset('img/gif/loading2.gif')}}" alt="Hola" id="carga_restore" style="width: 100px;height:100px;display:none;">
                    @if (isset($this->profile()->rol))
                    <div class="col" id="copia">
                        <form action="{{$this->route('configuracion/copia')}}" method="post" >
                            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                            <div class="form-group">
                                <label for="copia_" class="form-label"><b>Nombre de la copia - BD <span class="text-danger">(*)</span></b></label>
                                <input type="text" name="copia_" id="copia_" class="form-control" placeholder="Escriba el nombre de la copia de seguridad ...." autofocus>
                            </div>
                            <br>
                            <button class="btn-save float-end"><b>generar copia </b> <i class='bx bxs-data'></i></button>
                        </form>
                    </div>
                    @endif

                    <div class="col" id="restaurar" @if(isset($this->profile()->rol)) style="display:none" @endif>
                        <form action="{{$this->route('configuracion/restaurar')}}" method="post" enctype="multipart/form-data" id="form-restore">
                            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                         <div class="form-group">
                            <label for="archivo_bd" class="form-label"><b>Seleccione Archivo .sql</b></label>
                            <input type="file" name="archivo_bd" id="archivo_bd" class="form-control"  accept=".sql">
                         </div>
                         <br>
                         <button class="btn_blue float-end" id="restore_button"><b>Restaurar sistema </b> <i class='bx bx-refresh'></i></button>
                        @if (!isset($this->profile()->rol))
                        <a href="{{$this->route("login")}}" class="btn btn-rounded btn-outline-info float-end mx-2"><b>Iniciar sesión <i class='bx bx-log-in'></i></b></a>
                        @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
             
                <form action="" method="post" id="formulario_horario">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                        <div class="card-text"><h4>Configurar horarios de atención</h3></div>
                        <div class="row mb-3">
                
                            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-6 col-12">
                                <label for="dias_atencion" class="form-label">Días de atención(*)</label>
                                 <select name="dias_atencion" id="dias_atencion" class="form-select" autofocus>
                                    <option value="Lunes">Lunes</option>
                                    <option value="Martes">Martes</option>
                                    <option value="Miercoles">Miercoles</option>
                                    <option value="Jueves">Jueves</option>
                                    <option value="Viernes">Viernes</option>
                                    <option value="Sábado">Sábado</option>
                                    <option value="Domingo">Domingo</option>
                                 </select>
                            </div>
                
                            <div class="col-xl-4 col-lg-7 col-md-6 col-sm-6 col-12">
                                <label for="hora_inicio_atencion" class="form-label">Hora inicio(*)</label>
                                <input type="time" name="hora_inicio_atencion" id="hora_inicio_atencion" class="form-control"
                                value="08:00">
                            </div>
                
                            <div class="col-xl-4 col-12">
                                <label for="hora_cierre_atencion" class="form-label">Hora de Cierre(*)</label>
                                <input type="time" name="hora_cierre_atencion" id="hora_cierre_atencion" class="form-control"
                                value="16:00">
                            </div>
                        </div>
                
                        <div class="row lista" style="display: none">
                            <div class="card-text">
                               
                               <b class="text-info"> Lista de Horarios de atención</b>
                               <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th><b>Día de atención</b></th>
                                            <th><b>Hora Inicio</b></th>
                                            <th><b>Hora Final</b></th>
                                            <th><b>Quitar</b></th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-horarios"></tbody>
                                </table>
                               </div>
                            </div>
                        </div>
                      <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 m-1">
                            <button class="button-new" style="width: 100%" id="listar"> Agregar horario  <i class='bx bx-check'></i></button>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-1">
                            <button class="button-store" style="width: 100%;display: none" id="store_atencion"> Guardar horario  <i class='bx bx-save'></i></button>
                           </div>
                      </div>
 
                   </form>
            </div>
          
            <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
                <div class="card-text"><p class="h4">Configurar días laborables</p></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped nowrap" id="tabla_hora_atencion_" style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="d-none">ID</th>
                                <th>DÍA</th>
                                <th>HORARIO</th>
                                <th>LABORABLE?</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
          </div>
        </div>
      </div>
      {{-- modal para editar la configuración de días laborables con su horarios----}}
      <div class="modal" tabindex="-1" id="modal_config">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #FF7F50;">
              <h5 class="modal-title text-white">Editar horario de atención</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
             <div class="form-group">
                <label for="dia_editar" class="form-group"><b>Día</b></label>
                <input type="text" class="form-control" id="dia_editar" readonly style="border: 1px solid #4169E1">
             </div>
             <div class="form-group">
                <label for="hora_inicial_editar" class="form-group"><b>Hora inicio</b></label>
                <input type="time" class="form-control" id="hora_inicial_editar" style="border:1px solid #4169E1">
             </div>

             <div class="form-group">
                <label for="hora_cierre_editar" class="form-group"><b>Hora cierre</b></label>
                <input type="time" class="form-control" id="hora_cierre_editar" style="border:1px solid #4169E1">
             </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn-save" id="update_config_dia"> Guardar <i class='bx bx-save' ></i></button>
            </div>
          </div>
        </div>
      </div>
@endsection

@section('js')
<script>
     var RUTA = "{{URL_BASE}}" // la url base del sistema
     var TOKEN = "{{$this->Csrf_Token()}}";
     var Tabla_Horario_Dias_Atencion;
     var ID_DATA_CONFIG;
</script>
<script src="{{URL_BASE}}public/js/control.js"></script>
<script src="{{URL_BASE}}public/js/configuracion.js"></script>
<script>
    var Horarios=[];
    $(document).ready(function(){

    let Dias = $('#dias_atencion');

    let Hora_Inicio_Atencion = $('#hora_inicio_atencion');

    let Hora_Cierre_Atencion = $('#hora_cierre_atencion');

    let ButtonStore = $('#store_atencion');

    let TablaListaHorarios = $('#lista-horarios');

    /// dando click al boton listar
    $('#listar').click(function(evento){

        evento.preventDefault();

        /// verificamos que el campo de dias este rellenado

        if(Dias.val().trim().length > 0)
        {

            listarHorarios(Dias.val(),Hora_Inicio_Atencion.val(),Hora_Cierre_Atencion.val(),Horarios);
            
        }
        else{
            Dias.focus();
        }
    });

    $('#update_config_dia').click(function(){
        updateAtencion(ID_DATA_CONFIG,$('#hora_inicial_editar'),$('#hora_cierre_editar'),$('#dia_editar'));
    });

    ButtonStore.click(function(evento){

        evento.preventDefault();

       if(storeHorarioEsSalud() == 200)
       {
        Hora_Cierre_Atencion.focus();
        MessageRedirectAjax('success','Mensaje del sistema','Horarios registrados correctamente','layout-menu')

        
        // limpiamos todo
        Horarios =[];
        $('#lista-horarios').empty();
        Hora_Inicio_Atencion.val("08:00");
        Hora_Cierre_Atencion.val("16:00");
        $('.lista').hide(550);
        ButtonStore.hide(550)
       }
         
    });

    $('#restore_button').click(function(evento){
        evento.preventDefault();
        if($('#archivo_bd').val().trim().length == 0)
        {
            Swal.fire(
                {
                    title:'Mensaje del sistema!',
                    text:'Para importar los datos del sistema, proceda a seleccionar un archivo [.sql]',
                    icon:'error'
                }
            )
        }
        else
        {
            let  FormRestaurar = new FormData(document.getElementById('form-restore'));
            $.ajax(
                {
                    url:RUTA+"configuracion/restaurar",
                    method:"POST",
                    data:FormRestaurar,
                    processData: false,
                    contentType: false,
                    beforeSend:function(){
                        $('#carga_restore').show();
                    },
                    success:function(response){

                        response = JSON.parse(response);

                        if(response.mensaje === 'success')
                        {
                            Swal.fire({
                                title:"Mensaje del sistema!",
                                text:"Los datos se han importado correctamente al sistema",
                                icon:"success"
                            }).then(function(){
                                $('#carga_restore').hide();
                                $('#archivo_bd').val("");
                            });
                        }
                        else
                        {
                            if(response.mensaje === 'error')
                            {
                                alert("error")
                            }
                            else
                            {
                                alert("archivo vacio");
                            }
                        }
                    }
                }
            )
        }
    });

    $('#archivo_bd').change(function(){

       let NombreArchivo = this.files[0].name;

       let Extension = NombreArchivo.split(".");

       if(Extension[1]!== 'sql')
       {
        Swal.fire(
            {
                title:'Mensaje del sistema!',
                text:'Solo están permitidos archivos con extensión .sql',
                icon:'error'
            }
        )
       }
       
       return;
    });

    $('#opcion_sistema').change(function(){
     

        if($(this).val() === "1")
        {
            $('#copia').show(700);
            $('#copia_').focus();
            $('#restaurar').hide();
        }
        else
        {
            $('#copia').hide();
            $('#restaurar').show(700);
            $('#copia_').val("");
        }
    });

    /// pasar enter
    enter('dias_atencion','hora_inicio_atencion');
    enter('hora_inicio_atencion','hora_cierre_atencion');
    
    Hora_Cierre_Atencion.keypress(function(evento){

        if(evento.which == 13)
        {
            evento.preventDefault();

           /// verificamos que el campo de dias este rellenado
            
            if(Dias.val().trim().length > 0)
            {
             
            
            listarHorarios(Dias.val(),Hora_Inicio_Atencion.val(),Hora_Cierre_Atencion.val(),Horarios);
            
            
            }
            else{
            Dias.focus();
            }
        }
    });
    
    if("{{isset($this->profile()->rol)}}")
    {
         ConfigShowDiasLaborables();
         ConfigDiaLaborable();
    }
    editarDiasLaborEsSalud(Tabla_Horario_Dias_Atencion,'#tabla_hora_atencion_ tbody');
    });

    function ConfigShowDiasLaborables()
    {
         Tabla_Horario_Dias_Atencion = $('#tabla_hora_atencion_').DataTable({
            responsive:true,
            bDestroy:true,
            language:SpanishDataTable(),
      "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
            }],
            "order": [[1, 'asc']], /// enumera indice de las columnas de Datatable
            ajax:{
                url:RUTA+"configurar_dias_laborables?token_="+TOKEN,
                method:"GET",
                dataSrc:"response"
            },
            columns:[
                {"data":"dias_atencion"},
                {"data":"id_data_empresa"},
                {"data":"dias_atencion",render:function(dia){return '<span class="badge bg-danger" id="dia">'+dia+'</span>';}},
                {"data":null,render:function(horario){return '<span class="badge bg-info"><b id="ha_">'+horario.horario_atencion_inicial+' - '+horario.horario_atencion_cierre+'</b></span>';}},
                {"data":null,render:function(loborable){

                    let labor_confirm = '';

                    if(loborable.laborable === 'si')
                    {
                     labor_confirm = `
                     <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="config_dia_atencion`+loborable.id_data_empresa+`" checked>
                        <label class="form-check-label" for="config_dia_atencion`+loborable.id_data_empresa+`" style="cursor: pointer;" ><b>Laborable</b></label>
                        <button class="btn btn-rounded btn-outline-warning btn-sm" id="editar_dia_labor"> <i class='bx bxs-edit-alt'></i></button>
                    </div> 
                     `;
                    }
                    else
                    {
                        labor_confirm = `
                     <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="config_dia_atencion`+loborable.id_data_empresa+`">
                        <label class="form-check-label" for="config_dia_atencion`+loborable.id_data_empresa+`" style="cursor: pointer;" ><b class="text-danger">No Laborable</b></label>
                        <button class="btn btn-rounded btn-outline-warning btn-sm" id="editar_dia_labor"> <i class='bx bxs-edit-alt'></i></button>
                    </div> 
                     `; 
                    }
                    return labor_confirm;
                }
               }
            ],
      columnDefs:[
            { "sClass": "hide_me", target: 1 }
            ]
        });

   /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
        Tabla_Horario_Dias_Atencion.on( 'order.dt search.dt', function () {
        Tabla_Horario_Dias_Atencion.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
        } );
        }).draw();
    }

    /// editar dias laborables
    function editarDiasLaborEsSalud(Tabla,Tbody)
    {
        $(Tbody).on('click','#editar_dia_labor',function(){

            $('#modal_config').modal("show");
            let FilaSeleccionado = $(this).parents('tr');

            if(FilaSeleccionado.hasClass('child'))
            {
                FilaSeleccionado = FilaSeleccionado.prev();
            }

            ID_DATA_CONFIG = FilaSeleccionado.find('td').eq(1).text();
            let Dia_Config = FilaSeleccionado.find('td').eq(2).text();

            let HoraAtencionEsSalud = FilaSeleccionado.find("#ha_").text().split(" - ");

            let Hi_= HoraAtencionEsSalud[0];
            let Hf_ = HoraAtencionEsSalud[1];

            $('#dia_editar').val(Dia_Config); $('#hora_inicial_editar').val(Hi_);
            $('#hora_cierre_editar').val(Hf_);
            
            
        });
    }

    /// Habilitar y deshabilitar dias laborables
    function ConfigDiaLaborable()
    {
        $('#tabla_hora_atencion_').on('click','input[type=checkbox]',function(){

            let fila = $(this).parents('tr');

            if(fila.hasClass('child'))
            {
                fila = fila.prev();
            }

            let id_laborable =fila.find('td').eq(1).text();
            let dia = fila.find("#dia").text();
            
            if($(this).is(":checked"))
            {
                cambiarDiaAtencion(id_laborable,"si",dia);
            }
            else
            {
                cambiarDiaAtencion(id_laborable,"no",dia);
            }
            ConfigShowDiasLaborables();

        });
    }

     function cambiarDiaAtencion(id,estado,dia)
     {
        $.ajax({
            url:RUTA+"cambiar_dias_atencion_laborable_no_laborable/"+id+"/"+estado,
            method:"POST",
            data:{token_:TOKEN},
            success:function(response)
            {
                response = JSON.parse(response);
                if(response.response !== 'ok')
                { 
                    Swal.fire({
                        title:'Mensaje del sistema!',
                        text:'Error al intentar configurar los días de atención de EsSalud',
                        icon:'error'
                    })
                }
            }
        })
     }

     /// modificar los datos del horario de atención EsSalud
     function updateAtencion(id,hora_i,hora_f,dia)
     {
        $.ajax({
            url:RUTA+"configuracion/"+id+"/update",
            method:"POST",
            data:{token_:TOKEN,hi:hora_i.val(),hc:hora_f.val()},
            success:function(response)
            {
                response = JSON.parse(response);

                if(response.response === 'ok')
                {
                    Swal.fire(
                        {
                            title:'Mensaje del sistema!',
                            text:'El Horario de atención para el día '+dia+' se a modificado correctamente',
                            icon:'success',
                            target:document.getElementById('modal_config')
                        }
                    ).then(function(){
                        ConfigShowDiasLaborables();
                    });
                }
                else
                {
                    Swal.fire(
                        {
                            title:'Mensaje del sistema!',
                            text:'Error al modificar la hora de atención del día seleccionado',
                            icon:'error',
                            target:document.getElementById('modal_config')
                        }
                    )
                }
            }
        })
     }
</script>
@endsection