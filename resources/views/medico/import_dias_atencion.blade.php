@extends($this->Layouts("dashboard"))

@section("title_dashboard","import-dias-atención")

@section('css')
 
@endsection
 
@section("contenido")
 
<div class="col">
    <div class="card">
        <div class="card-body">
            <div class="card-text p-2">
                <h5>Importar dias de atención médica</h5>
            </div>
            <div class="card-text">
                <label for=""><b>Seleccione el archivo [excel]</b><b class="text-danger">(*)</b></label>
                <form action="" method="post" enctype="multipart/form-data" id="form_import_dias_atencion">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                    <div class="input-group">
                        <input type="file" class="form-control" name="excel" id="excel">
                        <button class="button-store" id="save_import">Importar <i class='bx bx-import' ></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    var RUTA = "{{URL_BASE}}"; // la url base del sistema
    var TOKEN = "{{$this->Csrf_Token()}}";
</script>
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>
$(document).ready(function(){

    $('#save_import').click(function(event){
    event.preventDefault();
    ImportarDiasAtencion();
    });
});

/**
 *Importar los días de atención del médico a traves de excel
 */
 function ImportarDiasAtencion()
{
    var FormularioData = new FormData(document.getElementById('form_import_dias_atencion'));
  
    $.ajax({
        url:RUTA+"medico/importar_dias_atencion",
        method:"POST",
        data:FormularioData,
        cache:false,
        contentType: false,
        processData: false,
        success:function(response){
            response = JSON.parse(response);

           if(response.response === 'vacio')
           {
            Swal.fire({
                title:"Advertencia!",
                text:"Seleccione un archivo donde tenga los días de atención, para guardarlos en el sistema",
                icon:"warning",
      
            });
           }else
           {
            if(response.response === 'error-tipo-archivo')
            {
                Swal.fire({
                title:"Advertencia!",
                text:"El archivo seleccionado es incorrecto,solo se permite un tipo de archivo excel",
                icon:"error",
             
            });
            }
            else{
                Swal.fire({
                title:"Advertencia!",
                text:"Los días de atención han sido importados correctamente",
                icon:"success",
     
            });
            }
           }
        }
    }).then(function(){
        showHorasProgramadosMedico(ATENCION_ID);
    });
    
}


</script>
@endsection