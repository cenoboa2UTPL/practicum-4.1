<?php 
namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\CitaMedica;
use models\Departamento;
use models\Paciente;
use models\Persona;
use models\TipoDocumento;
use models\Usuario;

class PacienteController extends BaseController
{
 
 private static array $ErrorExistencia = [];   
 private static $ModelPersona,$ModelPaciente,$ModelUser;
/*===============
MUESTRA EL FORMULARIO
DE CREAR PACIENTES
==================*/
public static function create()
{
    self::NoAuth();
    if (self::profile()->rol === self::$profile[0] || self::profile()->rol === self::$profile[1]) :
      self::$ModelPersona = new TipoDocumento;
      $TipoDocumentos = self::$ModelPersona->query()->get();
      self::View_("paciente.index",compact("TipoDocumentos"));
      return;
    endif;
    PageExtra::PageNoAutorizado();
}
/// proceso para registrar paciente
public static function save()
{
  self::NoAuth();
       /// validamos el token
   
      if(self::ValidateToken(self::post("token_")))
      {
        self::proccessSavePaciente();
      }
      
}

private static function proccessSavePaciente()
{
 self::$ModelPersona = new Persona;
 self::$ModelUser = new Usuario;
 self::$ModelPaciente = new Paciente;

 /// verificar la existencia por el # documento de la persona

  if(self::$ModelPersona->PersonaPorDocumento(self::post("documento")))
  {
   self::$ErrorExistencia[] = 'La persona con el # documento '.self::post("documento").' ya existe';
  }
 
  if(self::post("username")!= null and self::post("email")!=null)
  {
    if(self::$ModelUser->UsuarioPorUsername(self::post("username")))
    {
     self::$ErrorExistencia[] = 'El usuario con el nombre '.self::post("username").' ya está en uso';     
    }
   
    if(self::$ModelUser->UsuarioPorEmail(self::post("email"))) 
     {
       self::$ErrorExistencia[] = 'El usuario con el email'.self::post("email").' ya existe';  
     }
  }

   /// verificamos si existe errores de existencia de datos

   if(count(self::$ErrorExistencia) > 0)
   {
        self::json(["response"=>self::$ErrorExistencia]);
   }

   else
   {
        /// registramos al usuario
        if(self::post("username")!= null  and self::post("email")!=null and self::post("password")!=null)
        {
          self::$ModelUser->RegistroUsuario([self::post("username"),self::post("email"),password_hash(self::post("password"),PASSWORD_BCRYPT)],"paciente",null);
          /// registro de la persona
          $Usuario = self::$ModelUser->query()->Where("name","=",self::post("username"))->first();
          $user = $Usuario->id_usuario;
        }
        else{
          $user = null;
        }

        self::$ModelPersona->RegistroPersona([
                self::post("documento"),
                self::post("apellidos"),
                self::post("nombres"),
                self::post("genero"),
                self::post("direccion"),
                self::post("fecha_nac"),
                self::post("tipo_doc"),
                self::post("distrito"),
                $user
        ]);

        /// registrar al paciente

        $Persona = self::$ModelPersona->query()->Where("documento","=",self::post("documento"))->first();

        $Resouesta = self::$ModelPaciente->RegistroPaciente([
                self::post("telefono"),
                self::post("facebok"),
                self::post("wasap"),
                self::post("estado-civil"),
                self::post("apoderado"),
                $Persona->id_persona,
        ]);

        self::json(["response"=>$Resouesta]);

   }
}

/// mostrar todos los pacienets existentes
public static function PacientesExistentes()
{
  self::NoAuth();
  /// VALIDAMOS EL TOKEN
  if(self::ValidateToken(self::get("token_")))
  {
    self::$ModelPaciente = new Paciente;

    $Pacientes = self::$ModelPaciente->query()
                 ->Join("persona as per","pc.id_persona","=","per.id_persona")

                 ->Join("tipo_documento as tp","per.id_tipo_doc","=","tp.id_tipo_doc")
                 ->LeftJoin("distritos as dis","per.id_distrito","=","dis.id_distrito")
                 ->LeftJoin("provincia as pr","dis.id_provincia","=","pr.id_provincia")
                 ->LeftJoin("departamento as dep","pr.id_departamento","=","dep.id_departamento")
                 ->orderBy("pc.id_paciente","asc")
                 ->get();
  
    unset($Pacientes[0]->pasword);/// no consideramos el pasword
  
    self::json(compact("Pacientes"));
  }
}

/// actualzar datos del paciente
public static function modificarPaciente($personaId,$PacienteId)
{
  self::NoAuth();
  if(self::ValidateToken(self::post("token_")))
  {
    self::$ModelPersona = new Persona; self::$ModelPaciente = new Paciente;
    $respuesta = 0;

    /// verificamos si existe duplicidad en el # documento
    $ExisteNumDocumento = self::$ModelPersona->query()->Where("documento","=",self::post("doc"))->first();

    if($ExisteNumDocumento)
    {
      $respuesta = self::$ModelPersona->Update([
        "id_persona"=>$personaId,"apellidos"=>self::post("apellidos"),"nombres"=>self::post("nombres"),"genero"=>self::post("genero"),"direccion"=>self::post("direccion"),
        "fecha_nacimiento"=>self::post("fecha_nac"),"id_tipo_doc"=>self::post("tipo_doc"),
        "id_distrito"=>self::post("distrito")
      ]);
    }
    else
    {
        /// modificamos los datos de la persona
        $respuesta = self::$ModelPersona->Update([
          "id_persona" => $personaId, "documento" => self::post("doc"), "apellidos" => self::post("apellidos"), "nombres" => self::post("nombres"), "genero" => self::post("genero"),
          "direccion" => self::post("direccion"),"fecha_nacimiento" => self::post("fecha_nac"), 
          "id_tipo_doc" => self::post("tipo_doc"),"id_distrito" => self::post("distrito")
        ]);
    }

    if($respuesta)
    {
      /// actualizamos los datos del paciente
      self::$ModelPaciente->Update([
        "id_paciente"=>$PacienteId,"telefono"=>self::post("telefono"),"facebook"=>self::post("facebook"),
        "whatsapp"=>self::post("wasap"),"estado_civil"=>self::post("estado_civil"),
        "nombre_apoderado"=>self::post("apoderado")
      ]);

      self::json(['response'=>'success']);
    }else{self::json(['response'=>'error']);}
  }
}
/// método para visualizar la vista de completar datos del paciente
public static function completaDatos()
{
  self::NoAuth();

  if(self::profile()->rol === self::$profile[2] and !isset(self::profile()->id_persona))
  {
  $modelTipoDoc = new TipoDocumento; $modelDep = new Departamento;
  $TipoDocumentos = $modelTipoDoc->query()->get();
  $Departamentos = $modelDep->query()->Where("deleted_at","=","1")->get();

  return self::View_("usuario.Completar_Datos",compact("TipoDocumentos","Departamentos"));
  }
  else
  {
    PageExtra::PageNoAutorizado();
  }
}

// completar datos del paciente
public static function completeDatos()
{
self::NoAuth();
 
  if(self::ValidateToken(self::post("token_")))
  {
    
    $modelPersona = new Persona; $modelPaciente = new Paciente;
    
    # Validamos la existencia del paciente por # documento

    $Persona = $modelPersona->query()->Where("documento","=",self::post("doc"))->first();

    if($Persona)
    {
      self::json(['response'=>'existe']);
    }
    else
    {
      $Persona = self::post("persona");

      $Persona = explode(" ",$Persona); #Rosales Cadillo Abelardo Adrian

      // validamos que la persona hay proporcionado sus apellidos y nombres
      if(count($Persona) >= 2 and count($Persona)<=4)
      {
          $Apellidos = $Persona[0] . " " . $Persona[1];
          $Nombres = count($Persona) >2 ? $Persona[2] . " " . $Persona[3]:'';

          # registramos los datos
          $response = $modelPersona->RegistroPersona([
            self::post("doc"),
            $Apellidos, $Nombres, self::post("genero"), self::post("direccion"),
            self::post("fecha_nac"), self::post("tipo_doc"), self::post("distrito"),
            self::getSession("remember")
          ]);

          if ($response) {
            # registramos datos secundarios del paciente
            $Persona_ = $modelPersona->query()->Where("documento", "=", self::post("doc"))->first();

            $modelPaciente->RegistroPaciente([
              self::post("telefono"), self::post("facebok"), self::post("wasap"),
              self::post("estado_civil"), self::post("apoderado"), $Persona_->id_persona,
            ]);

            self::json(['response' => 'ok']);
          } else {
            self::json(['response' => 'error']);
          }
      }
      else
      {
        /// mostramos un mensaje de error para que complete los datos
        self::json(["response"=>"error-persona"]);
      }
    }
  }

}
# ver las citas registrados
public static function CitasRegistrados()
{
  self::NoAuth();
  if(self::profile()->rol === self::$profile[2] and isset(self::profile()->id_persona)):
    self::View_("paciente.citasregistrados");
  endif;
}

#devolver la data de la citas registrados del paciente
public static function DataCitasRegistrados()
{
  self::NoAuth();
  if(self::ValidateToken(self::get("token_")))
  {
    $modelData = new CitaMedica;
    $CitasRegistrados = $modelData->procedure("proc_show_citas_registrados","c",[self::profile()->id_usuario]);

    self::json(['response'=>$CitasRegistrados]);
  }
}
}