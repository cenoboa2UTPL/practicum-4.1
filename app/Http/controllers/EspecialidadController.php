<?php 
namespace Http\controllers;
use lib\BaseController;
use models\Especialidad;
use report\implementacion\Model;

class EspecialidadController extends BaseController
{

 private static $ModelEspecialidad;
 /** crear una nueva especialidad en la tabla especialidades */
 public static function store()
 {
    self::NoAuth();
    /// validar el token
    if(self::ValidateToken(self::post("token_")))
    {
        /// verificamos que no exista duplicidad

        self::$ModelEspecialidad = new Especialidad;

        self::json(['response'=>self::$ModelEspecialidad->create(self::post("especialidad"),self::post("precio"))]);
    }
 }
 
 /// proceso para modificar una especialidad
 public static function update($IdEspecialidad)
 {
    self::NoAuth();
    /// validamos el token
    if(self::ValidateToken(self::post("token_")))
    {
        self::$ModelEspecialidad = new Especialidad;

        /// modificamos la especialidad, retorna [1,0, existe]
        self::json(['response'=>self::$ModelEspecialidad->Modificar(self::post("especialidad"),self::post("precio"),$IdEspecialidad)]);
    }
 }

 /// Cambiar estado de una especialidad
 public static function CambiarEstadoEspecialidad($Id_Especialidad)
 {
    self::NoAuth();
    /// validamos el token, antes de deleiminar
    if(self::ValidateToken(self::post("token_")))
    {
        /// eliminamos
        self::$ModelEspecialidad = new Especialidad;

        self::json(['response'=>self::$ModelEspecialidad->CambiarEstado($Id_Especialidad,self::post("estado"))]);
    }
 }

 /// mostrar todas las especialidades
 public static function showEspecialidades()
 {
  self::NoAuth();
  /// Validamos el token 
  if(self::ValidateToken(self::get("token_")))
  {
    /// mostramos las especialidades
    self::$ModelEspecialidad = new Especialidad;

    $Especialidades = self::$ModelEspecialidad->query()->get();

    self::json(compact('Especialidades'));
  }

 }
 # MOSTRAR LAS ESPECIALIDADES PARA LAS CITAS MEDICAS
 public static function allEspecialidadesCitas()
 {
    self::NoAuth();

    if(self::profile()->rol === self::$profile[2] and isset(self::profile()->id_persona))
    {
    $EspecialidadesModel = new Especialidad;
    
    $especialidades = $EspecialidadesModel->query()
    ->Where("estado","=","1")
    ->get();

    return self::View_("cita_medica.especialidades_para_cita",compact("especialidades"));
    }
    else
    {
      self::RedirectTo("paciente/completar_datos");
    }
 }

 

}