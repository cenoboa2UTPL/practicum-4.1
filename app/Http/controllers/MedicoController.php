<?php 
namespace Http\controllers;

use DateTime;
use FPDF;
use Http\pageextras\PageExtra;
use lib\BaseController;
use models\AtencionMedica;
use models\CitaMedica;
use models\Configuracion;
use models\Especialidad;
use models\Especialidad_Medico;
use models\Medico;
use models\Persona;
use models\Plan_Atencion;
use models\Programar_Horario;
use models\Receta;
use models\Servicio;
use models\Usuario;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Windwalker\Utilities\Attributes\Prop;

use function Windwalker\where;

class MedicoController extends BaseController
{
private static array $ErrorExistencia = [];   

private static $ModelPersona,$ModelMedico,$ModelUser,$ModelEspecialidadMedico,$ModelEspecialidad,$ModelConfig,$ModelProgramHorario,$ModelCitaMedica,$ModelAtencionMedica,$ModelServicio,$Model;

private static string  $TipoArchivoAceptable = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
/*** MOSTRAR LA VISTA DE GESTIÓN DE MEDICOS */
public static function index()
{
    self::NoAuth();/// si no esta authenticado, redirige al login
    if(self::profile()->rol === self::$profile[0])
    {
      self::View_("medico.gestionmedico");
      return;
    }

    PageExtra::PageNoAutorizado();
}

/** MÉTODO PARA REGISTRAR A LOS MÉDICOS*/
public static function save()
{
  self::NoAuth();
 /// Validamos el token de envio
  if(self::ValidateToken(self::post("token_")))
  {
    /// Validamos antes de cargar la foto
   
    if(self::CargarFoto("foto") !== 'no-accept')/// osea si vacio tomara null 
    {
       self::proccessSaveMedico(self::getNameFoto()); 
    }
  }
 
}

private static function proccessSaveMedico($FotoUsuario)
{
 self::$ModelPersona = new Persona;
 self::$ModelUser = new Usuario;
 self::$ModelMedico= new Medico;

 /// verificar la existencia por el # documento de la persona

  if(self::$ModelPersona->PersonaPorDocumento(self::post("documento")))
  {
   self::$ErrorExistencia[] = 'La persona con el # documento '.self::post("documento").' ya existe';
  }
 
  if(self::$ModelUser->UsuarioPorUsername(self::post("username")))
  {
   self::$ErrorExistencia[] = 'El usuario con el nombre '.self::post("username").' ya está en uso';     
  }
 
  if(self::$ModelUser->UsuarioPorEmail(self::post("email"))) 
   {
     self::$ErrorExistencia[] = 'El usuario con el email'.self::post("email").' ya existe';  
   }

   /// verificamos si existe errores de existencia de datos

   if(count(self::$ErrorExistencia) > 0)
   {
        self::json(["response"=>self::$ErrorExistencia]);
   }

   else
   {
        /// registramos al usuario
        self::$ModelUser->RegistroUsuario([self::post("username"),self::post("email"),password_hash(self::post("password"),PASSWORD_BCRYPT)],"médico",$FotoUsuario);
        /// registro de la persona
        $Usuario = self::$ModelUser->query()->Where("name","=",self::post("username"))->first();

        self::$ModelPersona->RegistroPersona([
                self::post("documento"),
                self::post("apellidos"),
                self::post("nombres"),
                self::post("genero"),
                self::post("direccion"),
                self::post("fecha_nac"),
                self::post("tipo_doc"),
                self::post("distrito"),
                $Usuario->id_usuario
        ]);

        /// registrar al paciente

        $Persona = self::$ModelPersona->query()->Where("documento","=",self::post("documento"))->first();

        $Respuesta = self::$ModelMedico->RegistroMedico([
                self::post("telefono"),
                self::post("universidad"),
                self::post("experiencia"),
                $Persona->id_persona,
        ]);

        self::json(["response"=>$Respuesta]);

   }
}

/// Mostrar a los mèdicos existentes
public static function mostrarMedicos()
{
  self::NoAuth();
  // Validamos el token
  if(self::ValidateToken(self::get("token_")))
  {
    self::$ModelMedico = new Medico;

    $Medicos = self::$ModelMedico->query()->Join("persona as per","me.id_persona","=","per.id_persona")
               ->Join("usuario as us","per.id_usuario","=","us.id_usuario")
               ->Join("tipo_documento as tp","per.id_tipo_doc","=","tp.id_tipo_doc")
               ->Join("distritos as d","per.id_distrito","=","d.id_distrito")
               ->Join("provincia as pr","d.id_provincia","=","pr.id_provincia")
               ->Join("departamento as dep","dep.id_departamento","=","pr.id_departamento")
               ->orderBy("per.apellidos","asc")
               ->get();
  
    self::destroyVariableArray($Medicos,"pasword");
               
    self::json(compact("Medicos"));/// retorna en en formato JSON
  }
}


 /// mostramos las especialidades del médico que aún no tiene asignado

 public static function mostrarEspecialidadesNoAsignados(int|null $IdMedico,string $buscador)
 {
  self::NoAuth();
   /// validamos el token
   if(self::ValidateToken(self::get("token_")))
   {
    self::$ModelMedico = new Medico;
    /// obtenmos la data
    $Especialidades = self::$ModelMedico->procedure("proc_esp_not_asign_medico","c",[$IdMedico,'%'.$buscador.'%']);

    self::json(compact("Especialidades"));
   }
 }

 /// asignar especialidades al médico
 public static function AsignarEspecialidad()
 {
  self::NoAuth();
  /// validamos el token
  if(self::ValidateToken(self::post("token_")))
  {
    self::$ModelEspecialidadMedico = new Especialidad_Medico;

    self::json(['response'=>self::$ModelEspecialidadMedico->AsignEspecialidadMedico(self::post("especialidad"),self::post("medico"))]);
  }
 }
 /// método para mostrar los médicos y las especialidades
 public static function MostrarMedicoEspecialidades()
 {
  self::NoAuth();
    /// validamos el token

    if (self::ValidateToken(self::get("token_"))) {

      $DataMedicoJson = '';

      self::$ModelMedico = new Medico;

      self::$ModelEspecialidadMedico = new Especialidad_Medico;

      self::$ModelEspecialidad = new Especialidad;

      $Medicos = self::$ModelMedico->query()->join("persona as per", "me.id_persona", "=", "per.id_persona")->get();

    foreach ($Medicos as $medico) {
      $DataMedicoJson .= '{
      "id_medico":"' . $medico->id_medico . '",
      "apellidos":"' . $medico->apellidos . '",
      "nombres":"' . $medico->nombres . '",
      "especialidades":
      ';

        $DataMedicoJson .= '[';

        /// consultamos de la tabla medico especialidades

        foreach (self::$ModelEspecialidadMedico->query()->Where("id_medico", "=", $medico->id_medico)->get() as $especialidad_medico) {
          foreach (self::$ModelEspecialidad->query()->Where("id_especialidad", "=", $especialidad_medico->id_especialidad)->get() as $especialidad) {
              $DataMedicoJson .= '
              {
                "id_especialidad_medico":"' . $especialidad_medico->id_medico_esp . '",
                "id_especialidad":"' . $especialidad->id_especialidad . '",
                "nombre_especialidad":"' . $especialidad->nombre_esp . '"
              },';
          }
        }

        /// eliminamos la ultima coma

        $DataMedicoJson = rtrim($DataMedicoJson, ",");

        $DataMedicoJson .= ']},';
      }

      /// eliminamos la ultima coma

      $DataMedicoJson = rtrim($DataMedicoJson, ",");

      /// convertimos en un array json

      $DataMedicoJson = '{"medicos":[' . $DataMedicoJson . ']}';;

      echo $DataMedicoJson;
    }
  }

  /// devolvemos el horario de atención con respecto al día
  public static function getHoarioEsSaludPorDia()
  {
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
      self::$ModelConfig = new Configuracion;
      /// verificamos la existencia

      $Resultado=  self::$ModelConfig->getFilterDato(self::get("dia"));

      self::json(['response'=>$Resultado]);
    }
  }

  /// Asignar horarios de atención a cada médico

 public static function AsignarHorariosAtencion()
 {
  self::NoAuth();
  if(self::ValidateToken(self::post("token_")))
  {
    self::$ModelAtencionMedica = new AtencionMedica; 
    $Resultado = self::$ModelAtencionMedica
                 ->AsignaHorario(self::post("dia"),self::post("medico"),self::post("hi"),self::post("hf"),self::post("tiempo_atencion"));

   self::json(['response'=>$Resultado]);
  }
 }
  /// generar horario del médico
  public static function generateHorario()
  {
    self::NoAuth();
    if(self::ValidateToken(self::get("token_")))
    {
      $Horarios ='';
    $inicio = self::get('hi'); //$_POST['hora_inicio'] ?? '';
    $final =  self::get("hf");//$_POST['hora_final'] ?? '';
    $incr =  intval(self::get("intervalo"));//$_POST['tiempo_atencion'] ?? ''; // Minutos
    $Hora_Inicial = new DateTime($inicio);
    $Hora_Final = new DateTime($inicio);
    $Hora_inicial_generado = 0;
    $Hora_Final_generado = 0;
    while ($Hora_Final_generado < $final) {

      

      $Hora_Final->modify('+' . $incr . ' minutes');

      $Hora_inicial_generado = $Hora_Inicial->format('H:i:s');

      $Hora_Final_generado = $Hora_Final->format('H:i:s');

      /// personalizamos el json
      $Horarios.= '
      {
        "horario_inicial":"'.$Hora_inicial_generado.'",
        "horario_final":"'.$Hora_Final_generado.'"
      },';

      $Hora_Inicial->modify('+' . $incr . ' minutes');
     }
    
    $Horarios = rtrim($Horarios,",");
    echo '{"response":['.$Horarios.']}';
    }
  }
  /// guardar la programación de horarios del médico

  public static function guardarProgramacionDeHorarios()
  {
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::post("token_")))
    {
      /// guardamos la programación de horarios del médico
      self::$ModelProgramHorario = new Programar_Horario;

      /// atención médica
      $Atencion_ = self::post("atencion");
      /// Hora de inicio en la cúal se genera el horario de atención
      $Hora_Inicial = self::post("hi");
      /// Hora final en la cuál se genera el horario de atención
      $Hora_Final = self::post("hf");

      self::json(['response'=>self::$ModelProgramHorario->saveProgramacionHorario($Atencion_,$Hora_Inicial,$Hora_Final)]);
    }
  }

  /// mostrar las especialidades del médico

  public static function showEspecialidadesMedico(int $medico)
  {
    self::NoAuth();
    /// validamos el token
     
     if(self::ValidateToken(self::get("token_")))
     {
      self::$ModelEspecialidadMedico = new Especialidad_Medico;

      $Data = self::$ModelEspecialidadMedico->query()->Join("especialidad as esp","med_esp.id_especialidad","=","esp.id_especialidad")
      ->Join("medico as m","med_esp.id_medico","=","m.id_medico")
      ->select("id_medico_esp","med_esp.id_especialidad","nombre_esp")
      ->Where("m.id_medico","=",$medico)->get();

      self::json(['especialidades'=>$Data]);
     }
    
  }
  ///verificar si dicho procedimiento, ya existe en la especialidad del médico
  public static function verifyprocedimEspecialidad($medico,$procedim)
  {
    self::NoAuth();
    /// verifica el token
    if(self::ValidateToken(self::get("token_")))
    {
      self::$ModelServicio = new Servicio;
      /// consultar
      $Data = self::$ModelServicio->query()->Where("id_medico_esp","=",$medico)
      ->And("name_servicio","=",$procedim)->first();

      self::json(['response'=>$Data? true: false]);
    }
  }

  /// guadar procdimientos a la especialidad del médico
  public static function saveProcedimientoMedico()
  {
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::post("token_")))
    {
      self::$ModelServicio = new Servicio;

      $Resultado = self::$ModelServicio->Insert([
        "name_servicio"=>self::post("servicio"),
        "id_medico_esp"=>self::post("medico_esp")
      ]);

      self::json(['response'=>$Resultado]);
      
    }
  }

  /// mostrar los procedimiento de cada especialidad del médico
  public static function showProcedimientoMedico($id)
  {
    self::NoAuth();
    // validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
      self::$ModelServicio = new Servicio;
      // mostramos los datos de los procedimientos
      $Data = self::$ModelServicio->query()->Where("id_medico_esp","=",$id)->get();

      self::json(['response'=>$Data]);
    }
  }

  /// modificar procedimientos del médico
  public static function modificarProcedimiento($id)
  {
    self::NoAuth();
    /// validamos token
    if(self::ValidateToken(self::post("token_")))
    {
      self::$ModelServicio = new Servicio;

      $Respuesta = self::$ModelServicio->Update([
        "id_servicio"=>$id,
        "name_servicio"=>self::post("servicio")
      ]);

      self::json(['response'=>$Respuesta]);
    }
  }

  /// eliminar el procedimiento asignado al mèdico 
  public static function deleteProcedimiento($id)
  {
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::post("token_")))
    {

      /// verificamos que el procedimiento no estee en una cita respectivo
      self::$ModelCitaMedica = new CitaMedica;
      $ConsultaCitaMedica = self::$ModelCitaMedica->query()->
      Where("id_servicio","=",$id)->first();

      if($ConsultaCitaMedica)
      {
        self::json(['response'=>'existe']);
      }
      else
      {
        /// eliminamos dicho procedimiento asignado al médico
        self::$ModelServicio = new Servicio;

        $DeleteRespuesta = self::$ModelServicio->delete($id); 
        self::json(['response'=>$DeleteRespuesta]);
      }
    }
  }
  /// mostrar horarios disponibles del medico
  public static function showHorariosMedico()
  {
    self::NoAuth();

    if(self::profile()->rol === 'Médico')
    {
      self::$ModelMedico = new AtencionMedica;
      $user_id = self::profile()->id_usuario;
      $Horario_Medico = self::$ModelMedico->query()->Join("medico as m","atm.id_medico","=","m.id_medico")
      ->Join("persona as p","m.id_persona","=","p.id_persona")->Join("usuario as u","p.id_usuario","=","u.id_usuario")
      ->Where("u.id_usuario","=",$user_id)
      ->select("atm.id_atencion","m.id_medico","atm.dia","atm.hora_inicio_atencion","atm.hora_final_atencion")->get();
      self::View_("medico.horarios",compact("Horario_Medico"));
    }
  }

  /// mostrar los horarios programados del médico con respecto a un día

  public static function showHorariosProgramdosMedico($dia)
  {
    self::NoAuth();

    if(self::profile()->rol === 'Médico')
    {
      self::$ModelMedico = new Programar_Horario;
      $Horario_Medico_ = self::$ModelMedico->query()->Where("id_atencion","=",$dia)
      ->orderBy("hora_inicio","asc")
      ->get();

      self::json(['response'=>$Horario_Medico_]);
    }
  }

  /// activar y desactivar horarios del médico
  public static function active_desactive_horario_medico($id,$estado)
  {
    self::NoAuth();
    if(self::ValidateToken(self::post("token_")))
    {
      self::$ModelProgramHorario = new Programar_Horario;

      $resultado = self::$ModelProgramHorario->Update([
        "id_horario"=>$id,
        "estado"=>$estado
      ]);

      if($resultado)
      {
        self::json(['response'=>'ok']);
      }
      else
      {
        self::json(['response'=>'error']);
      }
    }
  }
  /// atención médica
  public static function atencion_medico_paciente()
  {
    self::NoAuth();
    self::$ModelConfig = new Configuracion;
    $FechaActual  = self::FechaActual("Y-m-d");

    $DiaActual = self::getDayDate($FechaActual);

    $Es_Dia_Laborable = self::$ModelConfig->query()->Where("dias_atencion","=",$DiaActual)->And("laborable","=","si")->first();

   if($Es_Dia_Laborable)
   {
    if(self::profile()->rol === self::$profile[3])
    {
      self::View_("medico.atencion_medica");
    }
    else
    {
      PageExtra::PageNoAutorizado();
    }
   }
   else{
    PageExtra::PageNoAutorizado();
   }
  }
  /// registrar la atencion médica del paciente
  public static function saveAtencionMedica(){

    self::NoAuth();

    if(self::ValidateToken(self::post("token_")))
    {
      self::$Model = new Plan_Atencion;

      # registramos la atención médica del paciente

      $respuesta = self::$Model->guardar(
        self::post("antecedente"), self::post("tiempo_enfermedad"), self::post("alergias"), self::post("interv_quir"), 
        self::post("vacuna"),self::post("examen_fisico"), self::post("diagnostico"), self::post("analisisConfirm"), 
        self::post("desc_analisis"), self::post("plan_tratamiento"),
        self::post("desc_tratamiento"), self::post("triaje"),self::post("proxima_cita")
      );

      if($respuesta)
      {
        // modificamos los estado de la cita médica a finalziado y el horario a disponible

        self::$ModelCitaMedica = new CitaMedica;

        self::$ModelProgramHorario = new Programar_Horario;

        self::$ModelCitaMedica->Update([
          "id_cita_medica"=>self::post("cita_medica"),
          "observacion"=>self::post("obs"),
          "estado"=>"finalizado",
          "id_horario"=>null
        ]);

        self::$ModelProgramHorario->Update([
          "id_horario"=>self::post("horario_id"),
          "estado"=>"disponible"
        ]);

        self::json(['response'=>'ok']);
      }
      else
      {
        self::json(['response'=>'error']);
      }
    }
  }

  /// registrar la receta del paciente
  public static function saveRecetaPaciente()
  {
    self::NoAuth();
    if(self::ValidateToken(self::post("token_")))
    {
      /// registramos la receta del paciente
      self::$Model = new Receta;
      self::$ModelAtencionMedica = new Plan_Atencion;

      $AtencionMedica = self::$ModelAtencionMedica->query()->Where("id_triaje","=",self::post("triaje_id"))->first();

      $resp = self::$Model->Insert([
        "medicamento"=>self::post("medic"),
        "dosis"=>self::post("dosis"),
        "tiempo_dosis"=>self::post("tiempo_dosis"),
        "cantidad"=>self::post("cantidad"),
        "id_atencion_medica"=>$AtencionMedica->id_atencion_medica
      ]);


      self::json(['resp'=>$resp]);
      
    }
  }

  /// ver pacientes atendidos por el médico
  public static function showPacientesAtendidos(int $opcion,string|null $fecha)
  {
    self::NoAuth();

    if(self::ValidateToken(self::get("token_")))
    {
      self::$Model = new Plan_Atencion;
      $usuario = self::profile()->id_usuario;

      $Pacientes_Atendidos = self::$Model->procedure("proc_pacientes_atendidos_medico","c",[$opcion,$fecha,$usuario]);

      self::json(['response'=>$Pacientes_Atendidos]);
    }
  }
  # mostrar los médicos por especialidad
  public static function medicoPorEspecialidad()
  {
    self::NoAuth();

    if(self::profile()->rol === self::$profile[2])
    {
      $ModelMedico = new Especialidad_Medico;

       if(!empty(self::get("esp_id")))
       {

        $medicos =  $ModelMedico->query()->Join("medico as m","med_esp.id_medico","=","m.id_medico")
        ->Join("especialidad as es","med_esp.id_especialidad","=","es.id_especialidad")
        ->Join("persona as p","m.id_persona","=","p.id_persona")->
        Join("usuario as u","p.id_usuario","=","u.id_usuario")->
        select("m.id_medico","p.apellidos","p.nombres","es.id_especialidad","u.foto","m.celular_num","es.nombre_esp","med_esp.id_medico_esp")
        ->where("es.id_especialidad","=",self::get("esp_id"))->get();

             
         self::View_("cita_medica.seleccionar_medico_cita",compact("medicos"));
       }else
       {
        self::RedirectTo("seleccionar-especialidad");
       }
       
       
    }
  }

  # VER EL PERFIL DEL MÉDICO
  public static function profileMedic(? string $id = null)
  {
    self::NoAuth();
 
    if(self::profile()->rol === self::$profile[2])
    {
      $modelMedico = new Medico;

      $medico = $modelMedico->query()->Join("persona as p","me.id_persona","=","p.id_persona")
      ->Join("usuario as u","p.id_usuario","=","u.id_usuario")
      ->Where("me.id_medico","=",$id)
      ->first();
      if($medico)
      {
        unset($medico->pasword);
        self::View_("medico.perfil",compact("medico")); 
      }
      else
      {
        self::RedirectTo("seleccionar-especialidad");
      }
    }
    else
    {
      PageExtra::PageNoAutorizado();
    }
  }
  /// agregar horario al médico
  public static function addPersonalizadoHourMedico($atencion)
  {
    self::NoAuth();
    if(self::ValidateToken(self::post("token_")))
    {
      $model = new Programar_Horario;

      $response = $model->Insert([
        "id_atencion"=>$atencion,
        "hora_inicio"=>self::post("hi"),
        "hora_final"=>self::post("hf"),
        "estado"=>"disponible"
      ]);

      if($response)
      {
        self::json(['response'=>'ok']);
      }
      else{
        self::json(['response'=>'error']);
      }
    }
  }
  # eliminar un horario del médico
  public static function deleteHorario($id)
  {
    self::NoAuth();

    if(self::ValidateToken(self::post("token_")))
    {
      $model = new Programar_Horario; $modelCita = new CitaMedica;

      $cita = $modelCita->query()->Where("id_horario","=",$id)->get();

      if(count($cita)>0){
        self::json(['response'=>'existe']);
      }
      else
      {
        $response = $model->delete($id);

        self::json(['response'=>$response?'ok':'error']);
      }
    }
  }
  /// modifcamos el horario
  public static function updateHorario($id)
  {
    self::NoAuth();

    if(self::ValidateToken(self::post("token_")))
    {
      $modelHorario = new Programar_Horario;

      $response = $modelHorario->Update(
        [
          "id_horario"=>$id,
          "hora_inicio"=>self::post("hi"),
          "hora_final"=>self::post("hf")
        ]
      );

      self::json(['response'=>$response?'ok':'error']);
    }
  }

  // importar los datos del horario
  public static function ImportarHorario()
  {
    self::NoAuth();
    if(self::ValidateToken(self::post("token_")))
    {
      /**
       * Obtenemos al file seleccionado, si o si tiene que ser un excel
       */
        if(self::file_size("file") > 0)
        {
          # Verificamos si el archivo seleccionado es un excel
          if(self::file_Type("file") === self::$TipoArchivoAceptable)
          {
            $ArchivoSelect = self::ContentFile("file");
            self::procesoImportHorario($ArchivoSelect); 
          }else
          {
            self::json(['response'=>"archivo no aceptable"]);
          }
        }
        else
        {
          self::json(['response'=>"vacio"]);
        }
    }
  }


  /**
   * Realizamos el proceso de importación del archivo excel
   */
  private static function procesoImportHorario($ArchivoExcel)
  {
    $modelHorario = new Programar_Horario;
    # recibimos el archivo excel seleccionado
    $DocumentExcel = IOFactory::load($ArchivoExcel);
    # indicamos la hoja 0, recomendable , debe de ser solo 1 hoja por documento
    $HojaData = $DocumentExcel->getSheet(0);
    #Obtenemos la cantidad de filas
    $FilasDocumento = $HojaData->getHighestDataRow();

    # recorremos
    for($fila = 2;$fila<=$FilasDocumento; $fila++)
    {
      $Hora = $HojaData->getCellByColumnAndRow(1,$fila);
      $Hora = explode("-",$Hora);
      $Hora_Inicial = $Hora[0];
      $Hora_Final = $Hora[1];
     
      if(!self::ExisteHorario(self::post("atencion_data"),$Hora_Inicial,$Hora_Final))
      {
        $Respuesta = $modelHorario->Insert([
          "id_atencion"=>self::post("atencion_data"),
          "hora_inicio"=>$Hora_Inicial,
          "hora_final"=>$Hora_Final
        ]);
      }
      else
      {
        $Respuesta = "existe";
      }
    }

    self::json(['response'=>$Respuesta]);
  }

  /**
   * Validamos si existe el horarios agregado
   */
  private static function ExisteHorario($atencion,$hi,$hf)
  {
    $modelH = new Programar_Horario;

    return $modelH->query()->where("id_atencion","=",$atencion)
    ->And("hora_inicio","=",$hi)
    ->And("hora_final","=",$hf)
    ->first();
  }

  /**
   * Este método muestra la vista para que el médico pueda
   * importar los datos de los días de atención desde un archivo excel
   */
  public static function ViewImportDiasDeAtencion()
  {
    self::NoAuth();
    if(self::profile()->rol === 'Médico')
    {
      self::View_("medico.import_dias_atencion");
    }
  }
  /**
   * Importar los días de atención del médico, seleccionando 
   * un archivo excel
   */
  public static function ImportDiasAtencion()
  {
    self::NoAuth();

    if(self::profile()->rol === self::$profile[3])
    {
      #Validar de que se haya seleccionado un archivo
      if(self::file_size("excel") > 0)
      {
        # Ahora validamos de que el archivo seleccionado sea si o si un excel
        if(self::file_Type("excel") === self::$TipoArchivoAceptable)
        {
          $ArchivoContent = self::ContentFile("excel");
          self::ProcesoImportDiasAtencion($ArchivoContent);
        }
        else
        {
          self::json(['response'=>"error-tipo-archivo"]);
        }
      }else
      {
        self::json(['response'=>'vacio']);
      }
    }
  }

  /**
   * Realizamos el proceso para importar los días de atención del médico
   */
  private static function ProcesoImportDiasAtencion($archivo)
  {
   $modelDiasAtencion = new AtencionMedica;

   $DocumentoExcel = IOFactory::load($archivo);

   $HojaActual = $DocumentoExcel->getSheet(0); # hoja 0 del archivo excel
   #obtenemos la cantidad de filas o registros de la hoja excel actual

   $FilasHojaExcel = $HojaActual->getHighestDataRow();

   $Medico_Id = self::MedicoData()->id_medico;

   for($fila_data = 2; $fila_data<=$FilasHojaExcel;$fila_data++)
   {
    $DiaAtencion = $HojaActual->getCellByColumnAndRow(1,$fila_data);
    $HorarioAtencion =  $HojaActual->getCellByColumnAndRow(2,$fila_data);
    $Intervalo_Horario_Atencion =   $HojaActual->getCellByColumnAndRow(3,$fila_data);

    /// Ahora desglosamos el horario de atención para obtener el inicio y final de la hora
    $HorarioAtencion = explode("-",$HorarioAtencion); # lo convierte en un array indexado
    $HoraInicio = $HorarioAtencion[0]; $HorarioFinal = $HorarioAtencion[1];

   # antes de insertar verificamos la existencia por día y médico
 
    # insertamos a la tabla atencionmedica , que indica los días de atención del médico
    
    if(!self::verificarExistenciaAtencion($Medico_Id,$DiaAtencion))
    {
        $Respuesta = $modelDiasAtencion->AsignaHorario(
        $DiaAtencion,self::MedicoData()->id_medico,$HoraInicio,$HorarioFinal,$Intervalo_Horario_Atencion
      );
    }
    else
    {
      $Respuesta = "existe";
    }
   }

   self::json(['response'=>$Respuesta?'ok':'error']);
  }
  private static function verificarExistenciaAtencion($medico,$dia)
  {
   $model = new AtencionMedica;
   return  $model->query()->Where("id_medico","=",$medico)
    ->And("dia","=",$dia)->first();
  }

  /** Realizamos la consulta el historial clínico de los pacientes */
  public static function showHistorialClinicoPaciente(string $pacienteDoc)
  {
    self::NoAuth();
    /// Validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
       
      $modelHistorial = new Plan_Atencion;

      /// obtenemos al médico logueado

      $MedicoId = self::MedicoData()->id_medico;

      $Historial = $modelHistorial->query()->Join("triaje as tr","plan.id_triaje","=","tr.id_triaje")
      ->Join("cita_medica as ct","tr.id_cita_medica","=","ct.id_cita_medica")
      ->Join("paciente as pc","ct.id_paciente","=","pc.id_paciente")
      ->Join("persona as p","pc.id_persona","=","p.id_persona")
      ->Join("especialidad as esp","ct.id_especialidad","=","esp.id_especialidad")
      ->Where("ct.id_medico","=",$MedicoId)
      ->and("p.documento","=",$pacienteDoc)
      ->select("ct.id_cita_medica","pc.id_paciente","concat(apellidos,' ',nombres) as paciente_",
      "ct.fecha_cita","esp.nombre_esp")
      ->get();
 


     self::json(['historial'=>$Historial]);
    }
  }

  /// traemos a los pacientes del médico
  public static function showPacientes()
  {
    self::NoAuth();
    /// Validamos el token
    if(self::ValidateToken(self::get("token_")))
    {
      $modelHistorial = new Plan_Atencion;

      /// obtenemos al médico logueado

      $MedicoId = self::MedicoData()->id_medico;

      $Historial = $modelHistorial->query()->distinct()->Join("triaje as tr","plan.id_triaje","=","tr.id_triaje")
      ->Join("cita_medica as ct","tr.id_cita_medica","=","ct.id_cita_medica")
      ->Join("paciente as pc","ct.id_paciente","=","pc.id_paciente")
      ->Join("persona as p","pc.id_persona","=","p.id_persona")
      ->Join("especialidad as esp","ct.id_especialidad","=","esp.id_especialidad")
      ->Where("ct.id_medico","=",$MedicoId)
      ->select("concat(apellidos,' ',nombres) as paciente_",
      "p.documento")
      ->get();

     self::json(['personas'=>$Historial]);
    }
  }

  /// ver el historial del paciente
  public static function reporteHistorialPaciente()
  {
    if(!empty(self::get('v')))
    {
     
     /// consultamos la base de datos para traer los datos
     $AtencionModel = new AtencionMedica;

     $MedicoId = self::MedicoData()->id_medico;
     $CitaMedicaId = self::get("v");

     $Historia = $AtencionModel->procedure("proc_historial_clinico","c",[$MedicoId,$CitaMedicaId]);

     /// Sacamos la edad del paciente

     $FechaNacimiento = explode("-",$Historia[0]->fecha_nacimiento); $Anio = $FechaNacimiento[0];
     $Mes = $FechaNacimiento[1]; $Dia = $FechaNacimiento[2];

     $FechaActual = explode("-",self::FechaActual("Y-m-d")); $AnioActual = $FechaActual[0];
     $MesActual = $FechaActual[1]; $DiaActual = $FechaActual[2];

     if($MesActual > $Mes and $DiaActual<=$Dia)
     {
      $EdadPaciente = ($AnioActual - $Anio);
      $EdadPaciente.=" Años";
 
     }
     else
     {
      $EdadPaciente = "Por cumplir ".$AnioActual - $Anio." Años";
     }

     /// obtenemos el estado civil del paciente 

     $EstadoCivil = $Historia[0]->estado_civil === 'se' ? 'No especifica':($Historia[0]->estado_civil=== 's'?'Soltero':
    ($Historia[0]->estado_civil === 'c'?'Casado':'Viudo'));

    /// sacamos la fecha en que se atendio el paciente

    $FechaAtencion = explode("-",$Historia[0]->fecha_cita); $FechaAtencion = $FechaAtencion[2]."/".$FechaAtencion[1]."/".$FechaAtencion[0];
 
    /// Fecha de la prócima cita
    $ProximaCitaFecha = explode("-",$Historia[0]->proxima_cita);$ProximaCitaFecha = $ProximaCitaFecha[2]."/".$ProximaCitaFecha[1]."/".$ProximaCitaFecha[0];
   
     $reporteHistorial = new FPDF('P', 'mm', "A3");
     $reporteHistorial->SetTitle(utf8__("Historial Clínico - ".$Historia[0]->apellidos." - ".$Historia[0]->nombres));
     $reporteHistorial->AddPage();/// agregamos una nueva página
     $reporteHistorial->Cell(130,40,$reporteHistorial->Image("public/asset/img/essalud.jpg",25,17,69,18),0,1);
     $reporteHistorial->SetFont("Arial","B",16);
     $reporteHistorial->Cell(290,0,utf8__("Historial Clínico"),0,1,"C");
     $reporteHistorial->Cell(290,10,"___________________",0,1,"C");
     $reporteHistorial->Ln(8);
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",14);
     $reporteHistorial->Cell(247,10,"Datos del paciente",1,1,"C");
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->SetX(25);
     $reporteHistorial->Cell(34,10,"Paciente",1,0,"L");
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(70,10,utf8__($Historia[0]->apellidos." ".$Historia[0]->nombres),1,0);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(36,10,"# Documento",1,0);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(33,10,$Historia[0]->documento,1,0);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(17,10,"Edad",1,0);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(57,10,utf8__($EdadPaciente),1,1);

     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(34,10,utf8__("Género"),1,0);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(70,10,$Historia[0]->genero=== '2'?'Femenino':'Masculino',1,0);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(36,10,utf8__("Estado civíl"),1,0);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(33,10,$EstadoCivil,1,0);

     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(33,10,utf8__("Hora atención"),1,0);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(41,10,$Historia[0]->hora_cita,1,1);
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(34,10,utf8__("Fecha atención"),1,0);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(70,10,utf8__(self::getFechaText($FechaAtencion)),1,0);

     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(36,10,"Apoderado",1,0);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(107,10,utf8__($Historia[0]->nombre_apoderado != null ? $Historia[0]->nombre_apoderado:'---------------------------------------------------------------'),1,1);

     /**
      * Datos de la taención médica
      */
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",14);
     $reporteHistorial->Cell(247,10,utf8__("Datos de la atención médica"),1,1,"C");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(247,10,utf8__("Motivo de la consulta"),1,1,"L");

     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->MultiCell(247,7,utf8__($Historia[0]->observacion != null ? $Historia[0]->observacion:'--------------------------------------------------------------------------------------'),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(247,10,utf8__("Antecedentes del paciente"),1,1,"L");

     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->MultiCell(247,7,utf8__($Historia[0]->antecedentes != null ? $Historia[0]->antecedentes:'--------------------------------------------------------------------------------------'),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(50,10,"Vacunas completas ?",1,0);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(10,10,ucwords($Historia[0]->vacunas_completos),1,0);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(50,10,utf8__("Requiere análisis"),1,0);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(10,10,ucwords($Historia[0]->requiere_analisis),1,0);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(65,10,utf8__("Intervenciones quirúrgicas"),1,0);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(62,10,ucwords($Historia[0]->intervensiones_quirugicas != null ? $Historia[0]->intervensiones_quirugicas:'No especifica'),1,1);

     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->MultiCell(247,7,utf8__("Tiempo de la enfermedad ó síntomas"),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->MultiCell(247,7,utf8__($Historia[0]->tiempo_enfermedad != null ? $Historia[0]->tiempo_enfermedad:'No especifica desde que tiempo son sus síntomas'),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->MultiCell(247,7,utf8__("Resultados del exámen físico"),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->MultiCell(247,7,utf8__($Historia[0]->resultado_examen_fisico != null ? $Historia[0]->resultado_examen_fisico:''),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->MultiCell(247,7,utf8__("Análisis"),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->MultiCell(247,7,utf8__($Historia[0]->desc_analisis_requerida == null ? 'No se le indicó  ningún análisis':$Historia[0]->desc_analisis_requerida),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->MultiCell(247,7,utf8__("Plan de tratamiento"),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->MultiCell(247,7,utf8__($Historia[0]->plan_tratamiento != null ? $Historia[0]->plan_tratamiento:'--------------------------------------------------------------------------'),1,"L");
     
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->MultiCell(247,7,utf8__("Descripción del tratamiento"),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->MultiCell(247,7,utf8__($Historia[0]->desc_plan != null ? $Historia[0]->desc_plan:'--------------------------------------------------------------------------'),1,"L");
     

     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->MultiCell(247,7,utf8__("Diagnóstico"),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->MultiCell(247,7,utf8__($Historia[0]->diagnostico != null ? $Historia[0]->diagnostico :'-------------------------------------------------------------------'),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->MultiCell(247,7,utf8__("Próxima cita "),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->MultiCell(247,7,utf8__("Asistir para el ".self::getFechaText($ProximaCitaFecha)),1,"L");
     
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",14);
     $reporteHistorial->MultiCell(247,7,utf8__("Signos vitales"),1,"C");
     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(82,7,utf8__("Presión arteria"),1,0,"L");
    
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(82,7,utf8__("Temperatura C°"),1,0,"L");

     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(83,7,utf8__("Frecuencia cardiaca"),1,1,"L");



     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(82,7,utf8__($Historia[0]->presion_arterial != null ? $Historia[0]->presion_arterial:'------------------------------------------------'),1,0,"L");

     
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(82,7,utf8__($Historia[0]->temperatura != null ? $Historia[0]->temperatura:'------------------------------------------------'),1,0,"L");

     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(83,7,utf8__($Historia[0]->frecuencia_cardiaca != null ? $Historia[0]->frecuencia_cardiaca:'------------------------------------------------'),1,"L");
     

     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(82,7,utf8__("Saturación de oxigeno"),1,0,"L");
    
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(82,7,utf8__("Talla"),1,0,"L");

     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(83,7,utf8__("Peso"),1,1,"L");



     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(82,7,utf8__($Historia[0]->saturacion_oxigeno != null ? $Historia[0]->saturacion_oxigeno:'------------------------------------------------'),1,0,"L");

     
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(82,7,utf8__($Historia[0]->talla != null ? $Historia[0]->talla." Cm":'------------------------------------------------'),1,0,"L");

     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(83,7,utf8__($Historia[0]->peso != null ? $Historia[0]->peso." Kg":'------------------------------------------------'),1,"L");
     
     

     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(123,7,utf8__("IMC"),1,0,"L");
    
     $reporteHistorial->SetFont("Arial","B",12);
     $reporteHistorial->Cell(124,7,utf8__("Estado Imc"),1,1,"L");

     

     $reporteHistorial->SetX(25);
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(123,7,utf8__($Historia[0]->imc != null ? $Historia[0]->imc:'------------------------------------------------'),1,0,"L");

     
     $reporteHistorial->SetFont("Arial","",12);
     $reporteHistorial->Cell(124,7,utf8__($Historia[0]->estado_imc != null ? $Historia[0]->estado_imc:'------------------------------------------------'),1,1,"L");
 
     /// medicamentos recetados

     $reporteHistorial->SetX(25);
     $reporteHistorial->setFont("Arial","B",14);
     $reporteHistorial->Cell(247,10,utf8__("Medicamentos recetados"),1,1,'C');

     $Receta = new Receta; $DetalleReceta_ = "";

     $DetalleReceta = $Receta->query()->Where("id_atencion_medica","=",$Historia[0]->id_atencion_medica)->get();

     if($DetalleReceta)
     {
      foreach($DetalleReceta as $receta_)
      {
        $DetalleReceta_.=utf8__(ucwords(strtolower($receta_->medicamento))."    -    tratamiento por ".$receta_->tiempo_dosis."    -    ".$receta_->cantidad." unidades").PHP_EOL;
      }
     }
     else
     {
      $DetalleReceta_ = "-------------------------------------------------------------------------------------------------";
     }

     $reporteHistorial->SetX(25);
     $reporteHistorial->setFont("Arial","",12);
     $reporteHistorial->MultiCell(247,7,$DetalleReceta_,1,'L');
     $reporteHistorial->Output();
      
    }
    else
    {
      self::RedirectTo("nueva_atencion_medica");
    }
  }
}