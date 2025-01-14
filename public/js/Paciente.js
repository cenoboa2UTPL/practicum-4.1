/// método que muestra a los tipos de documentos en format json
function ShowTipoDocumentos(id)
{
    /// inicializamos la option del select
    let option ="";
    
    $.ajax(
        {
          url:RUTA+"documentos-existentes?token_="+TOKEN,

          method:"GET",

          success:function(response)
          {
            response = JSON.parse(response)
            
            if(response.response.length > 0)
            {
                response.response.forEach(documento => {
                    
                    option+=
                     `
                      <option value=`+documento.id_tipo_doc+`>`+documento.name_tipo_doc.toUpperCase()+`</option>
                    `;
                });
            }
            $('#'+id).html(option)
          } 
        }
    )
}

/// método que muestrar los departamentos en format json
function ShowDepartamentos(idselect)
{
    /// inicializamos la option del select
    let option ="<option disabled selected> --- Seleccione --- </option>";
    
    $.ajax(
        {
          url:RUTA+"departamento/mostrar?token_="+TOKEN,

          method:"GET",

          success:function(response)
          {
            response = JSON.parse(response)
            
            if(response.response.length > 0)
            {
                response.response.forEach(documento => {
                    
                    option+=
                     `
                      <option value=`+documento.id_departamento+`>`+documento.name_departamento.toUpperCase()+`</option>
                    `;
                });
            }
            $('#'+idselect).html(option)
          } 
        }
    )
}

/// proceso para crear tipo de documentos(PETICIÓN AJAX)

function saveTipoDocumento(form_id)
{

  let response = crud(RUTA+"tipodoc/save",form_id);

   
     if(response == 1)
     {
      MessageRedirectAjax('success','Mensaje del sistema','Tipo de documento registrado','modal-crear-paciente')
      $('#'+form_id)[0].reset()
      ShowTipoDocumentos()
     }
     else
     {
      if(response === 'existe')
      {
       MessageRedirectAjax('warning','Mensaje del sistema','Tipo de documento ya existe','modal-crear-paciente')
      }
      else
      {
        MessageRedirectAjax('error','Mensaje del sistema','Error al registrar tipo documento ):','modal-crear-paciente')
      }
     }
}

/// proceso para crear departamentos(PETICIÓN AJAX)

function saveDepartamento(form_id)
{
  let response = crud(RUTA+"departemento/save",form_id);
  

     if(response == 1)
     {
      MessageRedirectAjax('success','Mensaje del sistema','Depatamento registrado','modal-crear-paciente')

      $('#'+form_id)[0].reset() /// reseteamos el form

      ShowDepartamentos('departamento'); /// mostrar los departamentos existentes para formulario de pacientes

      ShowDepartamentos('departamento_select') /// mostrar los departamentos existentes para formulario de provincias
      ShowDepartamentos('departamento_select_dis')
     }
     else
     {
      if(response === 'existe')
      {
       MessageRedirectAjax('warning','Mensaje del sistema','El deparatemento que deseas registrar ya existe','modal-crear-paciente')
      }
      else
      {
        MessageRedirectAjax('error','Mensaje del sistema','Error al registrar departamento ):','modal-crear-paciente')
      }
     }

}

/// registrar provincias

function saveProvincia(form_id)
{

  let response = crud(RUTA+"provincia/save",form_id);

  
  if(response == 1)
  {
   MessageRedirectAjax('success','Mensaje del sistema','Provincia registrado','modal-crear-paciente')

   $('#'+form_id)[0].reset() /// reseteamos el form

   allProvincias(); /// mostramos las provincias a registrar uno nuevo
   ShowDepartamentos('provincia_select_dis')
  }
  else
  {
   if(response === 'existe')
   {
    MessageRedirectAjax('warning','Mensaje del sistema','La Provincia que deseas registrar ya existe','modal-crear-paciente')
   }
   else
   {
     MessageRedirectAjax('error','Mensaje del sistema','Error al registrar provincia ):','modal-crear-paciente')
   }
  }
}

/// mostrar las provincias existentes por departamento

function showProvincias(id_dep,select_id)
{
  let option ="<option disabled selected>--- Seleccione ---</option>";
  
  let resultado = show(RUTA+"provincia/mostrar?token_="+TOKEN,{id_departamento:id_dep});
  
  if(resultado.length > 0)
  {
   resultado.forEach(provincia => {
    
    option+= "<option value="+provincia.id_provincia+">"+provincia.name_provincia.toUpperCase()+"</option>";

   });
   
  }
  $('#'+select_id).html(option);
}

/// crear distritos
function saveDistrito(form_id)
{
  let response = crud(RUTA+"distrito/save",form_id);
  

  if(response == 1)
  {
   MessageRedirectAjax('success','Mensaje del sistema','Distrito registrado correctamente','modal-crear-paciente')

   $('#name_distrito').val("");
   $('#name_distrito').focus();
  }
  else
  {
   if(response === 'existe')
   {
    MessageRedirectAjax('warning','Mensaje del sistema','El distrito que deseas registrar ya existe','modal-crear-paciente')
   }
   else
   {
     MessageRedirectAjax('error','Mensaje del sistema','Error al registrar distrito ):','modal-crear-paciente')
   }
  }
}

/// mostramos los distros por provincia

function showDistritos_Provincia(id_prov,select_id)
{
  let option ="<option disabled selected>--- Seleccione ---</option>";
  
  let resultado = show(RUTA+"distritos/mostrar-para-la-provincia/"+id_prov+"?token_="+TOKEN);

  if(resultado.length > 0)
  {
   resultado.forEach(distrito => {
    
    option+= "<option value="+distrito.id_distrito+">"+distrito.name_distrito.toUpperCase()+"</option>";

   });
   
  }
  $('#'+select_id).html(option);
}

/// registrar paciente

function savePaciente(form_id) {
  let li = "";
  let response = crud(RUTA + "paciente/save", form_id);

  if (typeof response === 'object') {
    response.forEach(error_existe => {

      li += `<li>` + error_existe + `</li>`;
    });

    $('#mensaje_error_existe').show(300)
    $('#mensaje_error_existe').html(li)
  }
  else {

    li = "";
    $('#mensaje_error_existe').hide(700)
    
    if(response == 1)
    {
      mostrarPacientes()
      MessageRedirectAjax('success','Mensaje del sistema','Paciente registrado correctamente','modal-crear-paciente');

      $('#'+form_id)[0].reset()
    }
    else
    {
      MessageRedirectAjax('error','Mensaje del sistema','Error al registrar paciente','modal-crear-paciente')
    }
  }

  /// subimos el scroll hacia arriba
  subidaScroll('.modal-body,html', 500)
}

/// mostrar los pacientes existentes
function mostrarPacientes()
{
  var Pacientes =  $('#tabla-pacientes').DataTable({

    retrieve:true,
    responsive:true,
    language:SpanishDataTable(),
    "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
    "order": [[1, 'asc']], /// enumera indice de las columnas de Datatable

    "ajax":{
      url:RUTA+"paciente-existentes?token_="+TOKEN,
      method:"GET",
      dataSrc:"Pacientes"
    },
    "columns":[
      {"data":"documento"},
      {"data":"name_tipo_doc",render:function(data){return data.toUpperCase()}},
      {"data":"documento",render:function(data){return data.toUpperCase()}},
      {"data":null,render:function(datas){return datas.apellidos.toUpperCase()+' '+datas.nombres.toUpperCase()}},
      {"data":"genero",render:function(data){return data == 1?'<span class="badge bg-primary">MASCULINO</span>':'<span class="badge bg-danger">FEMENINO</span>'}},
      {"data":"fecha_nacimiento",render:function(fn){
        if(fn == null)
        {
          return '<span class="badge bg-info">No especificó fecha nacimiento</span>';
        }
        let nueva_Fecha = fn.split("-");
        return nueva_Fecha[2]+"/"+nueva_Fecha[1]+"/"+nueva_Fecha[0];
      }},
      {"data":null,render:function(data){
        
        if(data.direccion == null)
        {
          return '<span class="badge bg-danger">No especifica su dirección </span>';
        }
        else{
          if(data.name_departamento == null && data.name_provincia == null && data.name_distrito == null && data.direccion!= null)
          {
            return '<span class="badge bg-danger">'+data.direccion.toUpperCase()+'</span>';
          }
          else
          {
            return data.direccion==null?'<span class="badge bg-danger">'+data.name_departamento.toUpperCase()+' - '+data.name_provincia.toUpperCase()+' - '+data.name_distrito.toUpperCase()+'</span>':data.name_departamento.toUpperCase()+' - '+data.name_provincia.toUpperCase()+' - '+' '+data.name_distrito.toUpperCase()+' - '+data.direccion.toUpperCase()
          }
        }

         
      }},
      {"data":"telefono",render:function(data){return data==null?'<span class="badge bg-danger">No especifica</span>':data.toUpperCase()}},
      {"data":"facebook",render:function(data){return data==null?'<span class="badge bg-danger">No especifica</span>':data.toUpperCase()}},
      {"data":"whatsapp",render:function(data){return data==null?'<span class="badge bg-danger">No especifica</span>':data.toUpperCase()}},
      {"data":"estado_civil",render:function(data){return data === 's'?'SOLTERO':data==='c'?'CASADO':data==='v'?'VIUDO':'NO ESPECIFICÓ';}},
      {"data":null,render:function(data)
      {
       if(data.fecha_nacimiento!=null)
       {
        Anio = data.fecha_nacimiento.split("-");

        /// obtenemos el año actua
        AnioActual = new Date();
 
        AnioActual = AnioActual.getFullYear()
 
        return '<span class="badge bg-success">'+(AnioActual-Anio[0])+'</span>';
       }
       return 'sin calcular'
      }},
      {"data":"nombre_apoderado",render:function(data){return data==null?'<span class="badge bg-danger">No especifica</span>':data.toUpperCase()}},
      {"defaultContent":`
        <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2">
        <button class="btn rounded btn-warning btn-sm" id="editar_paciente"><i class='bx bxs-edit-alt'></i></button>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-2">
        <button class="btn rounded btn-danger btn-sm"><i class='bx bxs-message-square-x'></i></button>
        </div>
        </div>
      `}
    ]
  }).ajax.reload();

  /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
  Pacientes.on( 'order.dt search.dt', function () {
    Pacientes.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    } );
  }).draw();
  /*======================================================================================*/
  EditarPaciente(Pacientes,'#tabla-pacientes tbody');
}
/** 
 * Editar datos específicos del paciente
 */
function EditarPaciente(Tabla,Tbody)
{
 $(Tbody).on('click','#editar_paciente',function(){

   // abrimos el modal de edición
   $('#modal_editar_paciente').modal('show');
   // obtenemos la fila seleccionado
   let filaSeleccionado = $(this).parents('tr');

   if(filaSeleccionado.hasClass('child'))
   {
    filaSeleccionado = filaSeleccionado.prev();
   }

   let Dato = Tabla.row(filaSeleccionado).data();

   PACIENTE_ID = Dato.id_paciente;
   PERSONA_ID = Dato.id_persona;

   /// input para editar datos del paciente
   $('#dni_editar').val(Dato.documento);$('#apellido_editar').val(Dato.apellidos);
   $('#nombre_editar').val(Dato.nombres);$('#genero_editar').val(Dato.genero);
   $('#direccion_editar').val(Dato.direccion);$('#fechanac_editar').val(Dato.fecha_nacimiento);
   $('#telefono_editar').val(Dato.telefono);$('#facebok_editar').val(Dato.facebook);
   $('#wasap_editar').val(Dato.whatsapp);$('#estado_civil_editar').val(Dato.estado_civil);
   $('#apoderado_editar').val(Dato.nombre_apoderado);$('#tipo_doc_editar').val(Dato.id_tipo_doc);
   
   // verificamos que el distrito exista
    
    $('#dep_editar').val(Dato.id_departamento); $('#prov_editar').val(Dato.id_provincia);
    $('#distrito_editar').val(Dato.id_distrito);
     
    
 });
}

/// actualizar datos del paciente
async function modificarDatosDelPaciente(documento_,apellidos_,nombre_,genero_,direccion_,fechanac_,tipodoc_,distrito_,telefono_,facebook_,wasap_,estado_civil_,apoderado_,persona,paciente)
{
  /// enviamos los datos en un formData
  data = new FormData();
  data.append('token_',TOKEN);data.append('doc',documento_.val()); data.append('apellidos',apellidos_.val());
  data.append('nombres',nombre_.val()); data.append('genero',genero_.val()); 
  data.append('direccion',direccion_.val());data.append('fecha_nac',fechanac_.val());
  data.append('tipo_doc',tipodoc_.val());data.append('distrito',distrito_.val());
  data.append('telefono',telefono_.val());data.append('facebook',facebook_.val());data.append('wasap',wasap_.val());
  data.append('estado_civil',estado_civil_.val());data.append('apoderado',apoderado_.val());

  const  resp = await axios.post(RUTA+'paciente/'+persona+'/'+paciente+'/update',data,function(){});

  if(resp.data.response === 'success')
  {
    Swal.fire(
      {
        title:'Mensaje del sistema!',
        text:'Datos del paciente modificados correctamente',
        icon:'success',
        target:document.getElementById('modal_editar_paciente')
      }
    ).then(function(){
      mostrarPacientes();
      $('#modal_editar_paciente').modal('hide');
    })
  }else
  {
    Swal.fire(
      {
        title:'Mensaje del sistema!',
        text:'Ocurrió un error al intentar actualizar los datos del paciente',
        icon:'error',
        target:document.getElementById('modal_editar_paciente')
      }
    )
  }
}

 

 
 