<?php 
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\CitaMedica;
use models\Medico;
use models\Paciente;
use models\Usuario;

class HomeController extends BaseController
{
    private static $Model;
    /// página de inicio sin iniciar session
    public static function PageInicio()
    {
         self::View_("home");
        // echo "pagina principal";
    }

    /// página del dashboard
    public static function Dashboard()
    {
        self::NoAuth();/// si no estas autneticado, redirige a login

        /// mostramos los pacientes atendidos de hoy

        $PacientesAtendidosHoy = self::CitaMedicaFinalizadoAnulado();
  
        /// citas médicas anulados
        $CitasMedicasAnuladosHoy = self::CitaMedicaFinalizadoAnulado("anulado");

        /// citas sin concluir
        $Citas_Sin_Concluir_Hoy = self::CitaMedicaFinalizadoAnulado("pagado");

        /// citas médicas pendientes
        $Citas_Medicas_Pendientes = self::CitaMedicaFinalizadoAnulado("pendiente");

        /// Pacientes examinados
        $Pacientes_Examinados = self::CitaMedicaFinalizadoAnulado("examinado");

        /// Pacientes atendidos por el médico authenticado
        $Pacientes_Atendidos_Medico = self::PacientesAtendidos();

        /// Pacientes por médico que fueron anulados su cita médica por no asistir

        $Pacientes_Anulados_Medico = self::PacientesAtendidos('count(*) as pacientes_atendidos','anulado');

        /// Monto recaudado por día del médico
        $MontoRecaudadoMedicoHoy = self::PacientesAtendidos("sum(monto_pago) as importe");

         /// Monto recaudado semanal del médico
         $MontoRecaudadoMedicoHoy = self::PacientesAtendidos("sum(monto_pago) as importe");

         /// total de citas médicas registrados del paciente

         $TotalDeCitasDelPacientes = self::showCitasPaciente();

         /// ver las citas concluidos del paciente authenticado
         $CitasConcluidosPaciente = self::showCitasPaciente('finalizado');

         /// Ver las citas no concluidos del paciente authenticado
         $CitasNoConcluidosPaciente = self::showCitasPaciente('anulado');
        /// obtener a los usuarios activos 
        $User_Active = self::UserActiveInactive();
        
        /// obtener a los usuarios inactivos en el sistema
        $User_Inactive = self::UserActiveInactive("2");

        /// Pacientes registrados
        self::$Model = new Paciente; $ModelMedico = new Medico;

        $PacientesExistentes = self::$Model->query()->select("count(*) as cantidad_paciente")->first();
        $MedicosExistentes = $ModelMedico->query()->select("count(*) as cantidad_medico")->first();
                                  
         
        self::View_("app",compact("PacientesAtendidosHoy","CitasMedicasAnuladosHoy","PacientesExistentes","MedicosExistentes","Citas_Sin_Concluir_Hoy","User_Active","User_Inactive","Citas_Medicas_Pendientes","Pacientes_Examinados","Pacientes_Atendidos_Medico","Pacientes_Anulados_Medico","MontoRecaudadoMedicoHoy","TotalDeCitasDelPacientes","CitasConcluidosPaciente","CitasNoConcluidosPaciente")); 
    }

    /// realizar consulta de citas médicas anulados y finalziados por día
    private static function CitaMedicaFinalizadoAnulado($estado = 'finalizado')
    {
        self::$Model = new CitaMedica;

        return self::$Model->query()->select("count(*) as cantidad")
               ->Where("ctm.fecha_cita","=",self::FechaActual("Y-m-d"))
               ->And("estado","=",$estado)->first();
    }

    /// obtener los usuario activos e inactivos
    private static function UserActiveInactive($estado = '1')
    {
        self::$Model = new Usuario;

        return self::$Model->query()->select("count(*) as cantidad_user")
               ->Where("estado","=",$estado)->first();
    }

    /// Pacientes atendidos por el médico authenticado
    private static function PacientesAtendidos($select="count(*) as pacientes_atendidos",$estado = 'finalizado')
    {
        $md = new CitaMedica;

        $User = self::profile()->id_usuario ?? 12;

        return $md->query()->Join("medico as m","ctm.id_medico","=","m.id_medico")
                ->select($select)
                ->Join("persona as p","m.id_persona","=","p.id_persona")
                ->Join("usuario as us","p.id_usuario","=","us.id_usuario")
                ->Where("ctm.fecha_cita","=",self::FechaActual("Y-m-d"))
                ->And("ctm.estado","=",$estado)
                ->And("us.id_usuario","=",$User)->first();
    }
    /// página del dashboard
    public static function Desktop()
    {
        self::NoAuth();/// si no estas autneticado, redirige a login
         
       if(self::profile()->rol === 'Director' or self::profile()->rol === 'Admisión' or self::profile()->rol === 'Enfermera-Triaje')
       {
        self::View_("Desktop"); 
       }
       else
       {
        PageExtra::PageNoAutorizado();
       }
    }

    /// mostrar pacientes de triaje
    public static function PacientesEnTriaje()
    {
        self::NoAuth();
        if(self::ValidateToken(self::get("token_")))
        {
            self::$Model = new CitaMedica;
            $Pacientes_Triaje = self::$Model->procedure("proc_pacientes_triaje_escritorio","c");
            self::json(['response'=>$Pacientes_Triaje]);
        }
    }

    /// pacientes que pasan a la atención médica
    public static function show_pacientes_en_atencion_medica()
    {
        self::NoAuth();

        if(self::ValidateToken(self::get("token_")))
        {
            self::$Model = new CitaMedica;
            $medico = self::request("medico");
            $Paciente_Cola_Atencion_Medica = self::$Model->procedure("proc_pacientes_atencion_medica","c",[$medico]);
            self::json(['response'=>$Paciente_Cola_Atencion_Medica]);
        }
    }

    /// ver reporte de total de citas totales, concluidad y no concluidas del paciente
    private static function showCitasPaciente(string $estado = '')
    {
        self::$Model = new CitaMedica;
        $paciente = self::profile()->id_persona ?? 12;
        if(empty($estado))
        {
            $Resultado = self::$Model->query()
                        ->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
                        ->Join("persona as p","pc.id_persona","=","p.id_persona")
                        ->select("count(*) as cantidad_citas")
                        ->Where("p.id_persona","=",$paciente)
                        ->first();
        }
        else
        {
            $Resultado = self::$Model->query()
                        ->Join("paciente as pc","ctm.id_paciente","=","pc.id_paciente")
                        ->Join("persona as p","pc.id_persona","=","p.id_persona")
                        ->select("count(*) as cantidad_citas")
                        ->Where("p.id_persona","=",$paciente)
                        ->And("ctm.estado","=",$estado)
                        ->first();
        }

       return $Resultado;
    }

    
    
}