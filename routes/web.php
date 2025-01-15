<?php 
use Http\controllers\{AuthController, ConfiguracionController,DepartamentoController,DistritoController,EspecialidadController,
    HomeController, InformeMedicoController, MedicoController,PacienteController,ProvinciaController,TipoDocumentoController,UsuarioController};
use lib\View;

/*========================================= inicializamos la sesion en caso no exista ==============================*/
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

 
/// ruta para la página principal

$route->get("/",[HomeController::class,'PageInicio']);
$route->get("/usuario/create",[UsuarioController::class,'create']);

 
/// ruta para el Dashboard

$route->get("/dashboard",[HomeController::class,'Dashboard']);
$route->get("/escritorio",[HomeController::class,'Desktop']);


//// rutas para tipo de documentos

$route->get("/new-tipo-documento",[TipoDocumentoController::class,'create']);

$route->post("/save-tipo-documento",[TipoDocumentoController::class,'save']);

$route->get("/tipo-documentos-existentes",[TipoDocumentoController::class,'index']);

$route->get("/documentos-existentes",[TipoDocumentoController::class,'showTipoDocumentos']);

$route->post("/update-tipo-documento/{id}",[TipoDocumentoController::class,'update']);

$route->post("/destroy-tipo-documento/{id}",[TipoDocumentoController::class,'delete_']);

$route->post("/saludar",[TipoDocumentoController::class,'saludar']);

$route->post("/tipodoc/save",[TipoDocumentoController::class,'save_tipo_doc']);

/// rutas para los pacientes

$route->get("/paciente",[PacienteController::class,'create']);
$route->get("/paciente-existentes",[PacienteController::class,'PacientesExistentes']);

$route->post("/paciente/save",[PacienteController::class,'save']);
$route->post("/paciente/completar_datos_",[PacienteController::class,'completeDatos']);

/// ruta para la vista del informe médico del paciente
$route->get("/paciente/consultar_informe_medico",[InformeMedicoController::class,'viewInformeMedico']);
$route->get("/paciente/show/informe_medico",[InformeMedicoController::class,'showInformeMedicoPaciente']);
/// modificar datos del paciente
$route->post("/paciente/{persona}/{paciente}/update",[PacienteController::class,'modificarPaciente']);

/// creamos la cuenta de usuario del paciente , desde el lógin
$route->post("/paciente/create_account",[AuthController::class,'saveAccountPaciente']);
$route->get("/paciente/confirm_account",[AuthController::class,'ConfirmAccountPaciente']);
$route->post("/paciente/verify_account_paciente",[AuthController::class,'VerifyCodUserPaciente']);

/// ver pdf del informe médico del paciente
$route->get("/informe_medico",[InformeMedicoController::class,'GenerateInformeMedicoPdf']);


/// rutas pata departamentos

$route->get("/departamentos",[DepartamentoController::class,'viewDepartementos']);
$route->post("/departemento/save",[DepartamentoController::class,'saveDepartamento']);
$route->get("/departamento/mostrar",[DepartamentoController::class,'showDepartamentos']);
$route->get("/departamento/eliminados",[DepartamentoController::class,'showDepartamentosEliminados']);
$route->post("/departamento/{id}/update",[DepartamentoController::class,'update']);
$route->post("/departamento/{id}/cambiar_estado/{estado}",[DepartamentoController::class,'cambiarEstado']);

/// rutas para provincias
$route->post("/provincia/save",[ProvinciaController::class,'saveProvincia']);
$route->get("/provincia/mostrar",[ProvinciaController::class,'showProvincias']);
$route->get("/provincia/mostrartodo",[ProvinciaController::class,'allProvincias']);
$route->get("/provincia/mostrartodo/eliminados",[ProvinciaController::class,'allProvinciasEliminados']);
$route->post("/provincia/{id}/update",[ProvinciaController::class,'update']);
$route->post("/provincia/{id}/modifystatus/{estatus}",[ProvinciaController::class,'CambiarEstatusProvincia']);

 // ruta para distritos
$route->post("/distrito/save",[DistritoController::class,'save']);

$route->get("/distritos/mostrar-para-la-provincia/{id_provincia}",[DistritoController::class,'showDistritos_provincia']);
$route->get("/distritos/all",[DistritoController::class,'showDistritosAll']);

/// rutas para médicos
$route->get("/medicos",[MedicoController::class,'index']);

/// ruta para los médicos

$route->post("/medico/save",[MedicoController::class,'save']);
$route->get("/medico/all",[MedicoController::class,'mostrarMedicos']);
$route->get("/medico/{id_medico}/{buscador}/especialidades-no-asignados",[MedicoController::class,'mostrarEspecialidadesNoAsignados']);
$route->post("/medico/asignar-especialidad",[MedicoController::class,'AsignarEspecialidad']);
$route->get("/medicos_y_sus_especialidades",[MedicoController::class,'MostrarMedicoEspecialidades']);
$route->get("/mostrar-horario-por-dia",[MedicoController::class,'getHoarioEsSaludPorDia']);
$route->post("/medico/asignar-horario_atencio-medica",[MedicoController::class,'AsignarHorariosAtencion']);
$route->get("/medico/generar_horarios",[MedicoController::class,'generateHorario']);
$route->post("/medico/programacion_horarios",[MedicoController::class,'guardarProgramacionDeHorarios']);
$route->get('/especialidades_del_medico/{medico}',[MedicoController::class,'showEspecialidadesMedico']);
$route->get("/verificar_existencia_de_procedimiento_medico/{medico}/{procedimiento}",[MedicoController::class,'verifyprocedimEspecialidad']);
$route->post("/medico/save/procedimientos_por_especialidad",[MedicoController::class,'saveProcedimientoMedico']);
$route->post("/medico/update/procedimientos_por_especialidad/{id}",[MedicoController::class,'modificarProcedimiento']);
$route->get("/{medico}/horarios",[MedicoController::class,'showHorariosMedico']);
$route->get("/medico/horarios_programados_por_dia/{dia}",[MedicoController::class,'showHorariosProgramdosMedico']);
$route->post("/medico/{id}/{estado}/cambiar_estado",[MedicoController::class,'active_desactive_horario_medico']);
$route->get("/medico_perfil/{id?}",[MedicoController::class,'profileMedic']);
$route->post("/medico/add_horario_personalizado/{atencion}",[MedicoController::class,'addPersonalizadoHourMedico']);
$route->post("/medico/{id}/eliminar_horario",[MedicoController::class,'deleteHorario']);
$route->post("/medico/{id}/modificar_horario",[MedicoController::class,'updateHorario']);
$route->post("/medico/importar_horario",[MedicoController::class,'ImportarHorario']);
$route->get("/medico/import-dias-de-atencion",[MedicoController::class,'ViewImportDiasDeAtencion']);
$route->post("/medico/importar_dias_atencion",[MedicoController::class,'ImportDiasAtencion']);
$route->get("/nueva_atencion_medica",[MedicoController::class,'atencion_medico_paciente']);

$route->post("/save_atencion_medica_paciente",[MedicoController::class,'saveAtencionMedica']);
$route->post("/save_receta_paciente",[MedicoController::class,'saveRecetaPaciente']);
$route->get("/atencion_medica/pacientes_atendidos/{opcion}/{fecha}",[MedicoController::class,'showPacientesAtendidos']);



/// rutas de especialidades
$route->post("/especialidad/save",[EspecialidadController::class,'store']);
$route->post("/especialidad/{id_especialidad}/update",[EspecialidadController::class,'Update']);
$route->post("/especialidad/{id_especialidad}/delete",[EspecialidadController::class,'CambiarEstadoEspecialidad']);
$route->get("/especialidad/all",[EspecialidadController::class,'showEspecialidades']);
$route->get('/show_procedimientos_medico/{id}',[MedicoController::class,'showProcedimientoMedico']);

/// ruta configuración datos de la empresa
$route->get("/Configurar-datos-empresa-EsSalud",[ConfiguracionController::class,'index']);
$route->get("/configurar_dias_laborables",[ConfiguracionController::class,'mostrar_dias_atencion']);
$route->post("/store-horario-essalud",[ConfiguracionController::class,'storeHorarioBusiness']);
$route->post("/cambiar_dias_atencion_laborable_no_laborable/{id}/{estado}",[ConfiguracionController::class,'cambiar_estado_dia_atencion']);
$route->post("/store-horario-essalud/existe",[ConfiguracionController::class,'storeHorarioBusiness']);
$route->get("/verificar-horario-before-list",[ConfiguracionController::class,'existeBeforeList']);
$route->get("/mostrar_dias/{medico}",[ConfiguracionController::class,'showDias']);


/// rutas para casos de procedimientos de las especialidades de los médicos
$route->post("/procedimiento/{id}/delete",[MedicoController::class,'deleteProcedimiento']);
$route->get("/horarios_no_programados/{medico}",[ConfiguracionController::class,'DiasAtencion_No_Programados']);
$route->get("/plantilla",function(){
 
    View::View_("email.reset_password");
});

 
 
