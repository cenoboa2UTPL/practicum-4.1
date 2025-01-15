<?php 
/*========================================= inicializamos la sesion en caso no exista ==============================*/

use Http\controllers\CitaMedicaController;
use Http\controllers\EspecialidadController;
use Http\controllers\HomeController;
use Http\controllers\InformeMedicoController;
use Http\controllers\MedicoController;
use Http\controllers\PacienteController;
use Http\controllers\RecetaController;
use Http\controllers\TriajeController;

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}


// rutas para el registro de de citas médicas
$route->get("/crear-nueva-cita-medica",[CitaMedicaController::class,'index']);
$route->get("/obtener_dia_segun_fecha",[CitaMedicaController::class,'obtenerDia']);
$route->get("/medicos_por_escpecialidad/{especialidad}",[CitaMedicaController::class,'mostrar_medicos_por_especialidad']);

$route->get("/procedimientos_por_medico/{id}",[CitaMedicaController::class,'verProcedimientosMedico']);

$route->get("/consulta_paciente/{documento}",[CitaMedicaController::class,'consultarPaciente']);
$route->get("/horarios_programador_del_medico/{medico}/{dia}",[CitaMedicaController::class,'horarios_disponibles_medico']);

$route->get("/obtener_precio_cita_medica/{id}",[CitaMedicaController::class,'getPrecio']);

$route->get("/citas-programados",[CitaMedicaController::class,'ver_citas_programados']);
$route->get("/citas-programados/all/{opcion}/{fecha}",[CitaMedicaController::class,'citas_Programados']);
$route->post("/cita_medica/save",[CitaMedicaController::class,'saveCitaMedica']);
$route->post("/anular_cita_medica",[CitaMedicaController::class,'AnularCitaMedica']);
$route->post("/confirmar_pago_cita_medica",[CitaMedicaController::class,'ConfirmarPagoCitaMedica']);
$route->get("/citas-programados-calendar",[CitaMedicaController::class,'citasMedicasCalendar']);
$route->get("/triaje/pacientes",[TriajeController::class,'index']);
$route->get("/triaje/pacientes/all",[TriajeController::class,'mostrarPacientesTriaje']);

// pacientes no atendidos 
$route->get("/cita_medica/pacientes_no_atendidos",[CitaMedicaController::class,'pacientes_no_atendidos']);

/// triaje
$route->post("/triaje/paciente/save",[TriajeController::class,'save']);
$route->get("/triaje/pacientes/desktop",[HomeController::class,'PacientesEnTriaje']);
$route->get("/triaje/{cita}/editar",[TriajeController::class,'consulta_triaje']);
$route->post("/triaje/{triaje_id}/update",[TriajeController::class,'update']);
$route->post("/paciente/save/proceso_cita_medica",[CitaMedicaController::class,'savePaciente']);
$route->get("/pacientes/cola/atencion_medica",[HomeController::class,'show_pacientes_en_atencion_medica']);

/// reportes para la receta medica
$route->get("/receta_medica",[RecetaController::class,'informe_receta_medica']);

/// modificar la cita médica
$route->post("/citamedica/{cita}/{hora_id}/update",[CitaMedicaController::class,'actualizarCitaMedica']);


/// registrar el informe médico del paciente
$route->post("/informe_medico/{atencion_medica}/save",[InformeMedicoController::class,'save']);
/// actualizar el informe médico
$route->post("/informe_medico/{id}/update",[InformeMedicoController::class,'update']);
/// verificamos existencia del registro del informe del paciente atendido
$route->get("/informe_medico/{id}/verificar_existencia",[InformeMedicoController::class,'verificarInforme']);

$route->get("/informe",[InformeMedicoController::class,'prueba']);

$route->get("/seleccionar-especialidad",[EspecialidadController::class,'allEspecialidadesCitas']);

$route->get("/seleccionar-medico",[MedicoController::class,'medicoPorEspecialidad']);

$route->get("/agendar_cita",[CitaMedicaController::class,'agendarCitaPaciente']);

$route->get("Buscar-paciente",[CitaMedicaController::class,'consultarPacienteCitaMedica']);

$route->get("citas-realizados",[PacienteController::class,'CitasRegistrados']);

$route->get("/citas-anulados",[CitaMedicaController::class,'citasAnulados']);
$route->post("/delete/{id}/citas_anulados",[CitaMedicaController::class,'DeleteCitasAnulados']);

$route->get("/citas_registrados_data",[PacienteController::class,'DataCitasRegistrados']);

/// citas por mes
$route->get("/citas_medicas_por_mes",[CitaMedicaController::class,'CitasMedicasPorMes']);

$route->get("/devuelve_fecha_texto/{fecha}",[TriajeController::class,'PrintFechaText']);
$route->get("/pacientes-triaje-personalizado/{fecha}",[TriajeController::class,'PacientesTriajePersonalizado']);

$route->get("prue",[TriajeController::class,'pruebas']);

/**HISTORIAL CLINICO */
$route->get("/ver-historial-del-paciente/{pacientedoc}",[MedicoController::class,'showHistorialClinicoPaciente']);
$route->get("/ver-pacientes_del_medico",[MedicoController::class,'showPacientes']);
$route->get("/paciente/historial",[MedicoController::class,'reporteHistorialPaciente']);