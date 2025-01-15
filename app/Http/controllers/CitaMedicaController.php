<?php 

namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\{CitaMedica, Configuracion, Especialidad, Especialidad_Medico, Paciente, Persona, Programar_Horario, Servicio, TipoDocumento};
 

class CitaMedicaController extends BaseController

{
    private static $ModelEspecialidad,$ModelService,$ModelMedicEsp,$ModelPersona,$Model,$ModelHp,$ModelTipoDoc;

    private static array $error = [];
    /// mostramos la vista para crear citas médicas
    public static function index()
    {
        self::NoAuth();

        if((self::profile()->rol === self::$profile[1] ||  self::profile()->rol === self::$profile[2]) and isset(self::profile()->id_persona))
        {
            self::$Model = new Configuracion;

            $FechaActual  = self::FechaActual("Y-m-d");

            $DiaActual = self::getDayDate($FechaActual);

            $Es_Dia_Laborable = self::$Model->query()->Where("dias_atencion","=",$DiaActual)->And("laborable","=","si")->first();

           if($Es_Dia_Laborable) # cambiamos de domingo a lunes
           {
                /*
            Le indicamos que solo el sistema se abrirá de lunes a sábado , que es el día de atención
            de EsSalud -Carhuaz
            */
                self::$ModelEspecialidad = new Especialidad;
                self::$ModelService = new Servicio;
                self::$ModelTipoDoc = new TipoDocumento;
                $Especialidades = self::$ModelEspecialidad->query()->get();
                $TipoDocumentos =  self::$ModelTipoDoc->query()->Where("estado", "=", "1")->get();
                self::View_("cita_medica.new", compact("Especialidades", "TipoDocumentos"));
           }
           else{
            PageExtra::PageNoAutorizado();
           }
        }
        else
        {
            PageExtra::PageNoAutorizado();
        }
    }

    /// obtener el día en letras , para consultarlo desde ajax
    public static function obtenerDia()
    {
        self::NoAuth();
        /// validamos el token
        if(self::ValidateToken(self::request("token_")))
        {
            $Dia = self::getDayDate(self::request("fecha"));

            self::json(['response'=>$Dia]);
        }
    }
    /// mostrar todos los médicos con respecto a una especialidad
    public static function mostrar_medicos_por_especialidad($especialidad = null)
    {
       self::NoAuth();
       /// validar el token
       if(self::ValidateToken(self::get("token_")))
       {
        self::$ModelMedicEsp = new Especialidad_Medico;
        $Medicos = self::$ModelMedicEsp->query()->
        Join("medico as m","med_esp.id_medico","=","m.id_medico")
        ->Join("persona as p","m.id_persona","=","p.id_persona")
        ->Where("med_esp.id_especialidad","=",$especialidad)
        ->select("med_esp.id_medico_esp","m.id_medico","med_esp.id_especialidad","concat(p.apellidos,' ',p.nombres) as medico")
        ->get();

        self::json(['medicos'=>$Medicos]);
       }  
         
    }

    /// ver los procedimiento que realiza el médico de acuerdo a la especialidad que tiene asignado
    public static function verProcedimientosMedico($id)
    {
      self::NoAuth();
      if(self::ValidateToken(self::get("token_")))
      {
        self::$ModelService = new Servicio;

        $Data = self::$ModelService->query()->Where("id_medico_esp","=",$id)->get();
        self::json(['response'=>$Data]);
      }
    }

    /// consultar la existencia de un paciente
    public static function consultarPaciente($documento)
    {
        self::NoAuth();
        // validamos el token
        if(self::ValidateToken(self::get("token_")))
        {
            self::$ModelPersona = new Persona;
            /// obtenemos el paciente
            $paciente = self::$ModelPersona->query()
            ->Join("paciente as pc","pc.id_persona","=","p.id_persona")
            ->LeftJoin("usuario as u","p.id_usuario","=","u.id_usuario")
            ->select("pc.id_paciente","p.id_persona","concat(p.apellidos,' ',p.nombres) as paciente","u.email")
            ->Where("p.documento","like",$documento)->get();

            if($paciente)
            {
                self::json(['response'=>$paciente]);
            }
            else
            {
                self::json(['response'=>'no existe']);
            }
        }
    }

    /// mostrar los horarios disponibles
    public static function horarios_disponibles_medico($medico,$dia)
    {
         self::NoAuth();

         if(self::ValidateToken(self::get("token_")))
         {
            // mostramos los horarios
            $fechaActual = self::FechaActual("Y-m-d");
            self::$Model = new Programar_Horario;
           
                $horarios = self::$Model->procedure("proc_horas_programadas_medico","c",[$medico,$dia,self::get("fecha")]);
            self::json(['response'=>$horarios]);
         } 
    }

    /// consultar precio

    public static function getPrecio($id)
    {
        self::NoAuth();
        if(self::ValidateToken(self::get("token_")))
        {
            /// consultamos el precio a pagar
             self::$ModelEspecialidad = new Especialidad;
             $precio = self::$ModelEspecialidad->query()->Where("id_especialidad","=",$id)->first();
             self::json(['response'=>$precio]);
        }
    }

    /// registrar cita médica
    public static function saveCitaMedica()
    {
        self::NoAuth();
        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new CitaMedica;
            self::$ModelHp = new Programar_Horario;


            /// registramos

            $Color_Texto = self::post("estado")=== 'pendiente'?'#FFFFFF':'#4169E1';
            $Color_Fondo = self::post("estado")=== 'pendiente'?'#FF4500':'#00FF7F';
            
            $Resultado = self::$Model->Insert([

                "fecha_cita"=>self::post("fecha"),
                "observacion"=>self::post("observacion"),
                "estado"=>self::post("estado"),
                "id_horario"=>self::post("id_horario"),
                "id_paciente"=>self::post("paciente"),
                "id_servicio"=>self::post("servicio"),
                "id_usuario"=>self::profile()->id_usuario,
                "color_texto"=>$Color_Texto,
                "color_fondo"=>$Color_Fondo,
                "hora_cita"=>self::post("hora_cita"),
                "monto_pago"=>self::post("monto"),
                "id_medico"=>self::post("medico"),
                "id_especialidad"=>self::post("especialidad")
            ]);

            if($Resultado == 1)
            {
                            /// actualizamos el estado
            self::$ModelHp->Update([
                "id_horario"=>self::post("id_horario"),
                "estado"=>"reservado"
            ]);

            /// ENVIAMOS CORREO
            if(self::profile()->rol === 'Paciente')
            {
             $FechaSend = explode("-",self::post("fecha")); $FechaSend_ = $FechaSend[2]."/".$FechaSend[1]."/".$FechaSend[0];
            self::send_(self::post("correo"),self::post("name_"),"Cita médica registrada",
            self::ContentAgendaCitaCorreo($FechaSend_,self::post("hora_cita"),self::post("doctora"),self::post("esp"),self::post("serv")=== '--- Seleccione ---'?'No especifica el servicio...':self::post("serv")));
            }
            self::json(['response'=>'ok']);
            }
            else
            {
                self::json(['response'=>'error']);
            }
        }
    }

    /// contendido de EsSalud para cita agendada del paciente
    private static function ContentAgendaCitaCorreo($fecha,$hora,$doctora,$especialidad,$servicio)
    {
        return
        '
        Hola, estima@ paciente '.self::profile()->name.' le acabamos de enviar
        el detalle de la reserva de la cita médica que acaba de reaizar,por favor no olvide en asistir puntual a
        para su atención médica, gracias!
        <table border="2px" style="width: 960px;">
        <thead style="background: #4169E1">
          <tr>
              <th style="color: #E0FFFF;">#</th>
              <th style="color: #E0FFFF;">FECHA</th>
              <th style="color: #E0FFFF;">HORA</th>
              <th style="color: #E0FFFF;">ESPECIALISTA</th>
              <th style="color: #E0FFFF;">ESPECIALIDAD</th>
              <th style="color: #E0FFFF;">SERVICIO</th>
          </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>'.self::getDayDate($fecha).' '.self::getFechaText($fecha).'</td>
            <td>'.$hora.'</td>
            <td>DR.'.$doctora.'</td>
            <td>'.$especialidad.'</td>
            <td>'.$servicio.'</td>
        </tr>
      </tbody>
       </table>
        ';
    }

    /// ver citas programados(la vista HTML)
    public static function ver_citas_programados()
    {
        self::NoAuth();

        self::$Model = new Configuracion;

        $FechaActual  = self::FechaActual("Y-m-d");

        $DiaActual = self::getDayDate($FechaActual);

        $Es_Dia_Laborable = self::$Model->query()->Where("dias_atencion","=",$DiaActual)->And("laborable","=","si")->first();

        if($Es_Dia_Laborable)
        {
            // verificamos que solo el usuario de rol Admisión pueda realizar esta operaci´ón
        if(self::profile()->rol === self::$profile[1] || self::profile()->rol === self::$profile[0])
        {
            self::View_("cita_medica.citas_programados");
        }
        else
        {
            PageExtra::PageNoAutorizado();
        }
        }
        else
        {
            PageExtra::PageNoAutorizado();
        }
    }

    /// mostrar citas programados
    public static function citas_Programados(string $opcion,$fecha='')
    {
       self::NoAuth();

       if(self::ValidateToken(self::get("token_")))
       {
        self::$Model = new Programar_Horario;

        $Citas_Programados = self::$Model->procedure("proc_citas_programados","c",[$opcion,$fecha]);

        self::json(['response'=>$Citas_Programados]);
       }
    }

    /// anular cita médica
    public static function AnularCitaMedica(){

        self::NoAuth();
        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new CitaMedica;
            self::$ModelHp = new Programar_Horario;

            $hora_cita = self::$ModelHp->Update([
                "id_horario"=>self::post("horario"),
                "estado"=>"disponible"
            ]);

           /// modificar el estado de la cita médica
           if($hora_cita)
           {
            $respuesta = self::$Model->Update([
                "id_cita_medica"=>self::post("cita"),
                "estado"=>"anulado",
                "color_texto"=>"#0000FF",
                "color_fondo"=>"#FF8C00"
               ]);
    
            self::json(['response'=>$respuesta]);
           }
        }
    }

     /// Confirmar pago de la cita médica reservada
     public static function ConfirmarPagoCitaMedica(){

        self::NoAuth();
        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new CitaMedica;

           /// modificar el estado de la cita médica
         
            $respuesta = self::$Model->Update([
                "id_cita_medica"=>self::post("cita"),
                "estado"=>"pagado",
                "color_texto"=>"#4169E1",
                "color_fondo"=>"#00FF7F"
               ]);
    
            self::json(['response'=>$respuesta]);
        }
    }

    /// mostrar las citas médicas 
    public static function citasMedicasCalendar()
    {
     self::NoAuth();
     
     if(self::ValidateToken(self::get("token_")))
     {
        self::$Model = new CitaMedica;
            $data = self::$Model->procedure("proc_show_calendar_citas","C");
            self::json($data);
     }
    }

    /// registrar a nuevos pacientes
    public static function savePaciente()
    {
        self::NoAuth();
        if(self::ValidateToken(self::post("token_")))
        {
            /// validamos los campos 
            if(empty(self::post("apell"))){self::$error [] = 'Complete los apellidos del paciente';}

            if(empty(self::post("nomb"))){self::$error [] = 'Complete los nombres del paciente';}

            if(empty(self::post("telefono"))){self::$error [] = 'Complete el # telefónico del paciente';}

            if(empty(self::post("wasap"))){self::$error [] = 'Complete su WhatsApp del paciente';}

            if(count(self::$error) > 0)
            {
                self::json(['response'=>self::$error]);
            }
            else
            {
                self::guardarPaciente();
            }
        }
    }

    /// proceso de registro de pacientes
    private static function guardarPaciente()
    {
        /// invocamos a los modelos
        self::$ModelPersona = new Persona;
        self::$Model = new Paciente;

        /// registramos a la persona

        $resultPersona = self::$ModelPersona->Insert([
            "documento"=>self::post("doc"),
            "apellidos"=>self::post("apell"),
            "nombres"=>self::post("nomb"),
            "genero"=>self::post("genero"),
            "direccion"=>self::post("direccion"),
            "id_tipo_doc"=>self::post("tipo_doc"),
            "id_distrito"=>self::post("distrito")
        ]);

        if($resultPersona)
        {
            $persona = self::$ModelPersona->query()->Where("documento","=",self::post("doc"))->first();

            $responsePaciente = self::$Model->Insert([
                "telefono"=>self::post("telefono"),
                "whatsapp"=>self::post("wasap"),
                "estado_civil"=>self::post("estado_civil"),
                "id_persona"=>$persona->id_persona
            ]);

            if($responsePaciente)
            {
                self::json(['response'=>'ok']);
            }
            else
            {
                self::json(['response'=>'error']);
            }
        }
    }

    /** 
     * Mostrar pacientes que no han sido atendidos
    */

    public static function pacientes_no_atendidos()
    {
        self::NoAuth();

       if(self::ValidateToken(self::get("token_")))
       {
       
            self::$Model = new CitaMedica;

            $Pacientes = self::$Model->procedure("proc_pacientes_pendientes_no_atendidos","C");

            self::json(['pacientes'=>$Pacientes]);
        
       }
    }

    /**
     * Actualizar la cita médica
     */
    public static function actualizarCitaMedica($id_cita,$Hora_Id_cita)
    {
        self::NoAuth();

        if(self::ValidateToken(self::post("token_")))
        {
            self::$Model = new CitaMedica; self::$ModelHp = new Programar_Horario;

            $DatosModificar = [
            "id_cita_medica"=>$id_cita,"fecha_cita"=>self::post("fecha_cita"),"observacion"=>self::post("obs"),
            "id_horario"=>self::post("horario"),"id_paciente"=>self::post("paciente"),"id_servicio"=>self::post("serv"),
            "hora_cita"=>self::post("horacita"),"monto_pago"=>self::post("monto"),"id_medico"=>self::post("medico"),
            "id_especialidad"=>self::post("esp")
            ];
            $respuesta = self::$Model->Update($DatosModificar);

            if($respuesta)
            {
                /// modificamos la tabla programación de horarios
               if(!is_null($Hora_Id_cita))
               {
                self::$ModelHp->Update([
                    "id_horario"=>$Hora_Id_cita,
                    "estado"=>"disponible",
                ]);

                self::$ModelHp->Update([
                    "id_horario"=>self::post("horario"),
                    "estado"=>"reservado",
                ]);
                

                ///modificamos el nuevo horario
                
               }

                self::json(['response'=>'ok']);
            }
            else
            {
                self::json(['response'=>'error']);
            }
        }
    }

    # paciente agenda la cita médica
    public static function agendarCitaPaciente()
    {
        
        self::NoAuth();

        if (self::profile()->rol === self::$profile[2]) {

            self::$Model = new Configuracion;

            $FechaActual  = self::FechaActual("Y-m-d");

            $DiaActual = self::getDayDate($FechaActual);

            $Es_Dia_Laborable = self::$Model->query()->Where("dias_atencion", "=", $DiaActual)->And("laborable", "=", "si")->first();

            if ($Es_Dia_Laborable) # cambiamos de domingo a lunes
            {

                self::Session("espec_id", self::get("espe_id"));
                self::Session("medic_id", self::get("medico_id"));
                self::Session("name_medico", self::get("medico"));
                self::Session("medico_esp_data",self::get("id_medesp_"));
                /*
            Le indicamos que solo el sistema se abrirá de lunes a sábado , que es el día de atención
            de EsSalud -Carhuaz
            */
                if (self::ExistSession("name_medico") and !empty(self::get("espe_id")) and !empty(self::get("medico_id"))) {
                    self::$ModelEspecialidad = new Especialidad;
                    self::$ModelService = new Servicio;
                    self::$ModelTipoDoc = new TipoDocumento;
                    $Especialidades = self::$ModelEspecialidad->query()->get();
                    $TipoDocumentos =  self::$ModelTipoDoc->query()->Where("estado", "=", "1")->get();
                    self::View_("cita_medica.new", compact("Especialidades", "TipoDocumentos"));
                } else {
                    PageExtra::PageNoAutorizado();
                }
            } else {
                PageExtra::PageNoAutorizado();
            }
        } else {
            PageExtra::PageNoAutorizado();
        }
    }

    # mostrar los pacientes para consultarlo
    public static function consultarPacienteCitaMedica()
    {
        self::NoAuth();

         if(self::ValidateToken(self::get("token_")))
         {
            if(self::profile()->rol === self::$profile[1])
            {
                self::$Model = new Paciente;
    
                $Pacientes = self::$Model->query()->Join("persona as p","pc.id_persona","=","p.id_persona")
                ->LeftJoin("usuario as u","p.id_usuario","=","u.id_usuario")
                ->select("pc.id_paciente","p.id_persona","concat(p.apellidos,' ',p.nombres) as paciente","p.documento","u.email")
                ->get();
    
                self::json(['pacientes'=>$Pacientes]);
            }else{
                PageExtra::PageNoAutorizado();
            }
         }
    }
# Citas médicas anulados
public static function citasAnulados()
{
    self::NoAuth();
    if(self::ValidateToken(self::get("mitoken_")))
    {
        if(self::profile()->rol === self::$profile[1]):
            $modelCitas = new CitaMedica;
      
            $Citas = $modelCitas->procedure("proc_show_citas_anulados","c");
      
            self::json(['citas_anulados'=>$Citas]);
          endif;
    }
}

# eliminar Citas médicas Agendas de estados anulado
public static function DeleteCitasAnulados($id)
{
    self::NoAuth();

    if(self::ValidateToken(self::post("token_")))
    {
        $modelCita = new CitaMedica;

        $response = $modelCita->delete($id);
        
        self::json(['response'=>$response?'ok':'error']);
    }
}
/**
 * Ver citas médicas registradas por cada mes de acuerdo a un año
 */
public static function CitasMedicasPorMes()
{
 self::NoAuth();
 
 $modelCitasMonth = new CitaMedica;

 $Respuesta = $modelCitasMonth->query()->Where("estado","=","finalizado")
 ->select("count(*) as cantidad","month(fecha_cita) as mes_number")
 ->GroupBy(["month(fecha_cita)","year(fecha_cita)"])->get();
 
 self::json(['response'=>$Respuesta]);
}
}