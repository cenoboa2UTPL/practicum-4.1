@extends($this->Layouts("dashboard"))

@section("title_dashboard","citas programados")

@section('css')
<style>
    input[type=radio]
    {
        width:23px;
        height:23px;
    }
    label{
        color: #4169E1;
    }
    label>b{
        color:#FF4500;
        cursor: pointer;
    }
    td.hide_me {
        display: none;
    }
</style>
@endsection 

@section("contenido")
<div class="card">
    <div class="card-text mx-3 mt-3">
        <p class="h4">Citas programados</p>
    </div>

    <div class="card-text mx-3 mt-3">
       Filtrar :

       <div class="row mt-2">
          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
            <div class="form-group">
                <label for="diario" class="form-label"><input type="radio" id="diario" name="opcion" checked><b>  Citas diarios (Hoy)</b></label>
               </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
           <div class="form-group">
            <label for="semanal" class="form-label"><input type="radio" id="semanal" name="opcion"><b>  Citas de esta semana</b></label>
           </div>
          </div>

          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
            <div class="form-group">
             <label for="mensual" class="form-label"><input type="radio" id="mensual" name="opcion"><b>  Citas de este mes</b></label>
            </div>
           </div>

           <div class="col-xl-4 col-lg-4 col-md-5 col-12">
            <div class="form-group">
             <label for="fecha_cita" class="form-label"><input type="radio" id="fecha_cita" name="opcion"><b> Citas por fecha personalizado</b></label>
            </div>
           </div>
           <div class="col-xl-7 col-lg-6 col-md-6 col-12" style="display: none" id="fecha_select">
            <div class="form-group">
             <input type="date" id="fecha" class="form-control" value="{{$this->FechaActual("Y-m-d")}}">
            </div>
           </div>
       </div>
      
    </div>

   <div class="card-text mx-3">
    <div class="table-responsive">
        <table class="table table-striped nowrap" id="Tabla_citas_programados" style="width: 100%"> 
            <thead>
                <tr>
                    <th>#</th>
                    <th class="d-none">ID_CITA</th>
                    <th class="d-none">ID_HORARIO</th>
                    <th>CEDULA</th>
                    <th>PACIENTE</th>
                    <th>ESPECIALIDAD</th>
                    <th>MÉDICO</th>
                    <th>FECHA DE LA CITA</th>
                    <th>HORA CITA</th>
                    <th>ESTADO</th>
                    <th>IMPORTE</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
        </table>
    </div>
   </div>
</div>
@endsection

@section('js')
<script>
 
  var RUTA = "{{URL_BASE}}" // la url base del sistema
  var TOKEN = "{{$this->Csrf_Token()}}"
</script>
<script src="{{URL_BASE}}public/js/control.js"></script>
    <script>
        $(document).ready(function(){
            showCitasProgramados("diario/diario");

            $('#semanal').click(function(){
                $('#fecha_select').hide(800);
                showCitasProgramados("semana/semana");
            });

            $('#diario').click(function(){
                $('#fecha_select').hide(800);
                showCitasProgramados("diario/diario");
            });

            $('#mensual').click(function(){
                $('#fecha_select').hide(800);
                showCitasProgramados("mensual/mensual");
            });
            $('#fecha_cita').click(function(){
                $('#fecha_select').show(800);
                showCitasProgramados("fecha/"+$('#fecha').val());
            });

            $('#fecha').change(function(){

                showCitasProgramados("fecha/"+$(this).val());
            });
            
            anularCitaMedica();
            confirmPagoCitaMedica();
        });

        function showCitasProgramados(opcion)
        {
            let TablaCitasProgramados = $('#Tabla_citas_programados').DataTable({
                responsive:true,
                autoWidth:true,
                processing:true,
                bDestroy:true,
                language:SpanishDataTable(),
               "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
                }],
                "order": [[1, 'asc']], /// enumera indice de las columnas de Datatable
                ajax:{
                    url:RUTA+"/citas-programados/all/"+opcion+"?token_="+TOKEN,
                    method:"GET",
                    dataSrc:"response",
                },
                columns:[
                    {"data":"documento"},
                    {"data":"id_cita_medica"},
                    {"data":"id_horario"},
                    {"data":"documento"},
                    {"data":"paciente"},
                    {"data":"especialidad",render:function(especialidad){return '<span class="badge bg-info">'+especialidad+'</span>';}},
                    {"data":"medico"},
                    {"data":"fecha_cita_"},
                    {"data":"hora_cita",render:function(hora){return '<span class="badge bg-primary">'+hora+'</span>';}},
                    {"data":"estado",render:function(estado){
                        if(estado==='pendiente')
                        {
                            return '<span class="badge bg-danger">Pendiente <i class="bx bxs-adjust-alt"></i></span>';
                        }
                        else
                        {
                            if(estado === 'pagado')
                            {
                                return '<span class="badge bg-success">Asistencia confirmado</span>';
                            }
                            else
                            {
                                if(estado === 'examinado')
                                {
                                    return '<span class="badge bg-info">Examinado <i class="bx bx-handicap"></i></span>';
                                }
                                else
                                {
                                    if(estado === 'anulado')
                                    {
                                        return '<span class="badge bg-warning">Anulado <i class="bx bx-x"></i></span>';
                                    }
                                    else{
                                        return '<span class="badge bg-primary">Finalizado <i class="bx bx-check"></i></span>';
                                    }
                                }
                            }
                        }
                        
                    }},
                    {"data":"monto_pago",render:function(importe){return '<span class="badge bg-info"> S/. '+importe;}},
                    {"data":null,render:function(dta){
                        let button = `<div class="row">`;
                        if(dta.estado === 'pagado')
                        {
                          button+=  ` 
                          <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2">
                            <button class="btn rounded btn-danger btn-sm" id='anular'><i class='bx bx-x'></i> Anular</button>
                          </div>
                          `
                        }
                        else{
                            if(dta.estado === 'pendiente')
                            {
                            button+=`
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2">
                            <button class="btn rounded btn-outline-info btn-sm" id='confirm_pago'><b>Confirmar <i class='bx bx-check'></i></b></button>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2" id='anular'>
                            <button class="btn rounded btn-danger btn-sm"><i class='bx bx-x'></i> Anular</button>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2" id='wasap'>
                            <a href="https://api.whatsapp.com/send?phone=`+dta.whatsapp+`&text='Hola,estimad@ paciente `+dta.paciente+`', solo para hacerle acordar que hoy tiene una cita desde las `+dta.hora_cita+` con el médico `+dta.medico+`, por favor asistir puntual, muchas gracias!!!" target="_blank" class="btn rounded btn-success btn-sm"><i class='bx bxl-whatsapp'></i> enviar mensaje</a>
                          </div>
                            `;
                            }
                        }
                        return button;
                    }}
                ],
                columnDefs:[
                    { "sClass": "hide_me", targets: [1,2] }
                ]
            });

             /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
             TablaCitasProgramados.on( 'order.dt search.dt', function () {
             TablaCitasProgramados.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
            } );
            }).draw();
        }

        /// anular cita médica
        function anularCitaMedica()
        {
            $('#Tabla_citas_programados').on('click','#anular',function(){
               
                /// obtener la fila seleccionada
                let fila = $(this).closest('tr');

                if(fila.hasClass('child'))
                {
                    fila = fila.prev();
                }

                /// obtenemos los datos
                let Horario_Id_cita = fila.find('td').eq(2).text();/// id del horario de la cita médica
                let Id_Cita_Medica_ = fila.find('td').eq(1).text(); /// el id de la cita médica
                let paciente = fila.find('td').eq(4).text(); /// dato del paciente
                 
                ConfirmAnularCitaMedica(Horario_Id_cita,Id_Cita_Medica_,paciente)

                
            });
        }

        /// confirmar pago del paciente que realizó una cita médica sin realizar el pago
         /// confirmar pago del paciente que realizó una cita médica sin realizar el pago
         function confirmPagoCitaMedica()
        {
            $('#Tabla_citas_programados').on('click','#confirm_pago',function(){
               
               /// obtener la fila seleccionada
               let fila = $(this).closest('tr');

               if(fila.hasClass('child'))
               {
                   fila = fila.prev();
               }

               /// obtenemos los datos
               let Id_Cita_Medica_ = fila.find('td').eq(1).text(); /// el id de la cita médica
               let paciente = fila.find('td').eq(4).text(); /// dato del paciente
                
               ConfirmPagoCitaMedica(Id_Cita_Medica_,paciente);
               
           });
        }

        function ConfirmAnularCitaMedica(hora_cita,cita_medica,paciente)
        {
         Swal.fire({
            title: 'Estas seguro de anular la cita médica del paciente '+paciente,
            text: "Al anular la cita médica del paciente , el horario con lo cuál realizó su cita, volverá a estar disponible y su cita se anulará automaticamente!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#008000',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, anular!'
            }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url:RUTA+"anular_cita_medica",
                method:"POST",
                data:{token_:TOKEN,horario:hora_cita,cita:cita_medica},
                success:function(data_)
                {
                    data_ = JSON.parse(data_);

                    if(data_.response == 1)
                    {
                        Swal.fire({
                            title:"Mensaje del sistema",
                            text:"La cita médica del paciente "+paciente+" se ha anulado correctamente",
                            icon:"success"
                        }).then(function(){
                            showCitasProgramados("diario/diario");
                        });
                    }
                    else
                    {
                        Swal.fire({
                            title:"¡ADVERTENCIA!",
                            text:"Error al intentar anular la cita médica del paciente "+paciente,
                            icon:"error"
                        })  
                    }
                }
              })
            }
            })
        }

        function ConfirmPagoCitaMedica(cita_medica,paciente)
        {
         Swal.fire({
            title: 'Estas seguro de confirmar la asistencia del paciente: '+paciente,
            text: "Al confirmar la asistencia del paciente, pasará directamente a triaje para ser examinado por una enfermera, y luego pasará directo con el médico que solicitó!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#00BFFF',
            cancelButtonColor: '#FF4500',
            confirmButtonText: 'Si, confirmar!'
            }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url:RUTA+"confirmar_pago_cita_medica",
                method:"POST",
                data:{token_:TOKEN,cita:cita_medica},
                success:function(data_)
                {
                    data_ = JSON.parse(data_);

                    if(data_.response == 1)
                    {
                        Swal.fire({
                            title:"Mensaje del sistema",
                            text:"La asistencia del paciente "+paciente+" a sido verificado correctamente",
                            icon:"success"
                        }).then(function(){
                            showCitasProgramados("diario/diario");
                        });
                    }
                    else
                    {
                        Swal.fire({
                            title:"¡ADVERTENCIA!",
                            text:"Error al intentar validar la asistencia del paciente "+paciente,
                            icon:"error"
                        })  
                    }
                }
              })
            }
            })
        }
    </script>
@endsection