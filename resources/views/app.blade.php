@extends($this->Layouts("dashboard"))

@section('title_dashboard', 'Dashboard')

 
@section('contenido')
@if ($this->profile()->rol === 'Médico')
<div class="row">
  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
    <div class="card text-white" style="background-color: rgb(13, 112, 212)">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
            src="{{$this->asset('img/icons/unicons/medico.ico')}}"
            alt="chart success"
            class="rounded"
          />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
            </button>
             
          </div>
        </div>
        <span class="fw-semibold d-block mb-1 text-center">PACIENTES ATENDIDOS</span>
        <h3 class="card-title mb-2 text-white text-center">{{$Pacientes_Atendidos_Medico->pacientes_atendidos}}</h3>
      </div>
    </div>
  </div>  
  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
    <div class="card text-white" style="background-color: rgb(205, 87, 111)">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
            src="{{$this->asset('img/icons/unicons/paciente.ico')}}"
            alt="chart success"
            class="rounded"
          />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
            </button>
             
          </div>
        </div>
        <span class="fw-semibold d-block mb-1 text-center">PACIENTES ANULADOS</span>
        <h3 class="card-title mb-2 text-white text-center">{{$Pacientes_Anulados_Medico->pacientes_atendidos}}</h3>
      </div>
    </div>
  </div>

  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
    <div class="card text-white" style="background-color: rgb(37, 143, 16)">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
            src="{{$this->asset('img/icons/unicons/money.ico')}}"
            alt="chart success"
            class="rounded"
          />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
            </button>
             
          </div>
        </div>
        <span class="fw-semibold d-block mb-1 text-center">MONTO RECAUDADO</span>
        <h3 class="card-title mb-2 text-white text-center">S./ {{$MontoRecaudadoMedicoHoy->importe== null? '0.00':$MontoRecaudadoMedicoHoy->importe}}</h3>
      </div>
    </div>
  </div>

  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
    <div class="card text-white" style="background-color: rgb(26, 188, 123)">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
            src="{{$this->asset('img/icons/unicons/money.ico')}}"
            alt="chart success"
            class="rounded"
          />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded text-white"> (SEMANAL)</i>
            </button>
             
          </div>
        </div>
        <span class="fw-semibold d-block mb-1 text-center">MONTO RECAUDADO</span>
        <h3 class="card-title mb-2 text-white text-center">S./ 0.00</h3>
      </div>
    </div>
  </div>

  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
    <div class="card text-white" style="background-color: rgb(6, 109, 235)">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
            src="{{$this->asset('img/icons/unicons/money.ico')}}"
            alt="chart success"
            class="rounded"
          />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded text-white"> (MENSUAL)</i>
            </button>
             
          </div>
        </div>
        <span class="fw-semibold d-block mb-1 text-center">MONTO RECAUDADO</span>
        <h3 class="card-title mb-2 text-white text-center">S./ 0.00</h3>
      </div>
    </div>
  </div>

</div>
@endif
@if ($this->profile()->rol ==='Admisión' || $this->profile()->rol === 'Enfermera-Triaje')
<div class="row">
  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
    <div class="card bg bg-danger text-white">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
            src="{{$this->asset('img/icons/unicons/citas.ico')}}"
            alt="chart success"
            class="rounded"
          />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
            </button>
             
          </div>
        </div>
        <span class="fw-semibold d-block mb-1 text-center">CITAS MÉDICAS ANULADOS</span>
        <h3 class="card-title mb-2 text-white text-center">{{$CitasMedicasAnuladosHoy->cantidad}}</h3>
      </div>
    </div>
  </div>

  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
    <div class="card bg bg-success text-white">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
            src="{{$this->asset('img/icons/unicons/money.ico')}}"
            alt="chart success"
            class="rounded"
          />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
            </button>
             
          </div>
        </div>
        <span class="fw-semibold d-block mb-1 text-center">CITAS MÉDICAS PAGADOS</span>
        <h3 class="card-title mb-2 text-white text-center">{{$Citas_Sin_Concluir_Hoy->cantidad}}</h3>
      </div>
    </div>
  </div>

  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
    <div class="card text-white" style="background-color: crimson">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
            src="{{$this->asset('img/icons/unicons/notificacion.ico')}}"
            alt="chart success"
            class="rounded"
          />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
            </button>
             
          </div>
        </div>
        <span class="fw-semibold d-block mb-1 text-center">CITAS MÉDICAS PENDIENTES</span>
        <h3 class="card-title mb-2 text-white text-center">{{$Citas_Medicas_Pendientes->cantidad}}</h3>
      </div>
    </div>
  </div>

  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
    <div class="card text-white" style="background-color: rgb(20, 220, 217)">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
            src="{{$this->asset('img/icons/unicons/pacientes_examinados.ico')}}"
            alt="chart success"
            class="rounded"
          />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
            </button>
             
          </div>
        </div>
        <span class="fw-semibold d-block mb-1 text-center">PACIENTES EXAMINADOS</span>
        <h3 class="card-title mb-2 text-white text-center">{{$Pacientes_Examinados->cantidad}}</h3>
      </div>
    </div>
  </div>

  <div class="col-xl-4 col-lg-4 col-12 my-1">
    <div class="card text-white" style="background-color: rgb(20, 60, 220)">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
            src="{{$this->asset('img/icons/unicons/medico.ico')}}"
            alt="chart success"
            class="rounded"
          />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
            </button>
             
          </div>
        </div>
        <span class="fw-semibold d-block mb-1 text-center">PACIENTES ATENDIDOS</span>
        <h3 class="card-title mb-2 text-white text-center">{{$PacientesAtendidosHoy->cantidad}}</h3>
      </div>
    </div>
  </div>
</div>
@endif
  
@if ($this->profile()->rol === 'Director')
<div class="row">
  <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 my-1">
      <div class="card bg bg-primary text-white">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <img
                src="{{$this->asset('img/icons/unicons/paciente.ico')}}"
                alt="chart success"
                class="rounded"
              />
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="cardOpt3"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
              </button>
               
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">PACIENTES ATENDIDOS</span>
          <h3 class="card-title mb-2 text-white text-center">{{$PacientesAtendidosHoy->cantidad}}</h3>
        </div>
      </div>
    </div>
  
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 my-1">
      <div class="card bg bg-danger text-white">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <img
              src="{{$this->asset('img/icons/unicons/citas.ico')}}"
              alt="chart success"
              class="rounded"
            />
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="cardOpt3"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
              </button>
               
            </div>
          </div>
          <span class="fw-semibold d-block mb-1 text-center">CITAS ANULADOS</span>
          <h3 class="card-title mb-2 text-white text-center">{{$CitasMedicasAnuladosHoy->cantidad}}</h3>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 my-1">
      <div class="card bg bg-primary">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <img
                src="{{$this->asset('img/icons/unicons/paciente.ico')}}"
                alt="chart success"
                class="rounded"
              />
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="cardOpt3"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
              </button>
               
            </div>
          </div>
          <span class="fw-semibold d-block mb-1 text-center text-white">PACIENTES</span>
          <h3 class="card-title mb-2 text-white text-center">{{$PacientesExistentes->cantidad_paciente}}</h3>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 my-1">
      <div class="card bg bg-primary">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <img
              src="{{$this->asset('img/icons/unicons/medico.ico')}}"
              alt="chart success"
              class="rounded"
            />
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="cardOpt3"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
              </button>
               
            </div>
          </div>
          <span class="fw-semibold d-block mb-1 text-center text-white">MÉDICOS EXISTENTES</span>
          <h3 class="card-title mb-2 text-white text-center">{{$MedicosExistentes->cantidad_medico}}</h3>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
      <div class="card bg bg-warning">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <img
                src="{{$this->asset('img/icons/unicons/notificacion.ico')}}"
                alt="chart success"
                class="rounded"
              />
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="cardOpt3"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
              </button>
               
            </div>
          </div>
          <span class="fw-semibold d-block mb-1 text-center text-white">CITAS SIN CONCLUIR</span>
          <h3 class="card-title mb-2 text-white text-center">{{$Citas_Sin_Concluir_Hoy->cantidad}}</h3>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
      <div class="card bg bg-info">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <img
              src="{{$this->asset('img/icons/unicons/admin_user_man_22187.ico')}}"
              alt="chart success"
              class="rounded"
            />
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="cardOpt3"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
              </button>
               
            </div>
          </div>
          <span class="fw-semibold d-block mb-1 text-center text-white">USUARIOS ACTIVOS</span>
          <h3 class="card-title mb-2 text-white text-center">{{$User_Active->cantidad_user}}</h3>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-lg-4  col-12 my-1">
      <div class="card" style="background-color: rgb(227, 116, 19)">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <img
                src="{{$this->asset('img/icons/unicons/admin_user_man_22187.ico')}}"
                alt="chart success"
                class="rounded"
              />
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="cardOpt3"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
              </button>
               
            </div>
          </div>
          <span class="fw-semibold d-block mb-1 text-center text-white">USUARIOS INACTIVOS</span>
          <h3 class="card-title mb-2 text-white text-center">{{$User_Inactive->cantidad_user}}</h3>
        </div>
      </div>
    </div>
    

 </div>
@endif

{{--- PARTE PRINCIPAL DEL PACIENTE ---}}
@if ($this->profile()->rol === 'Paciente')
<div class="row">
  <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
    <div class="card bg bg-primary">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
            src="{{$this->asset('img/icons/unicons/cita_.ico')}}"
            alt="chart success"
            class="rounded"
          />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
            </button>
             
          </div>
        </div>
        <span class="fw-semibold d-block mb-1 text-center text-white">Citas registrados</span>
        <h3 class="card-title mb-2 text-white text-center">{{$TotalDeCitasDelPacientes->cantidad_citas}}</h3>
      </div>
    </div>
  </div> 
  
<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
    <div class="card bg bg-info">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
            src="{{$this->asset('img/icons/unicons/cita_.ico')}}"
            alt="chart success"
            class="rounded"
          />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
            </button>
             
          </div>
        </div>
        <span class="fw-semibold d-block mb-1 text-center text-white">Citas concluidos</span>
        <h3 class="card-title mb-2 text-white text-center">{{$CitasConcluidosPaciente->cantidad_citas}}</h3>
      </div>
    </div>
  
</div>

<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
  <div class="card bg bg-danger">
    <div class="card-body">
      <div class="card-title d-flex align-items-start justify-content-between">
        <div class="avatar flex-shrink-0">
          <img
          src="{{$this->asset('img/icons/unicons/citas.ico')}}"
          alt="chart success"
          class="rounded"
        />
        </div>
        <div class="dropdown">
          <button
            class="btn p-0"
            type="button"
            id="cardOpt3"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
          </button>
           
        </div>
      </div>
      <span class="fw-semibold d-block mb-1 text-center text-white">Citas no concluidos</span>
      <h3 class="card-title mb-2 text-white text-center">{{$CitasNoConcluidosPaciente->cantidad_citas}}</h3>
    </div>
  </div>

</div>
</div> 
@endif
<div class="row mt-3 d-none">
  <div class="col-xl-6 col-lg-6 col-md-6 col-12">
  <div class="card" style="background:#F8F8FF">
    <div class="card-body">
       <div class="cars-text p-2"><h5>Citas por mes</h5></div>
       <div class="card-text">
        <canvas id="myChart"></canvas>
       </div>
    </div>
  </div>
  </div>
</div>
@endsection

@section('js')

<script src="{{$this->NodeModule("chart.js/dist/chart.umd.js")}}"></script>  
<script>
   var URL = "{{URL_BASE}}";
   var TOKEN = "{{$this->Csrf_Token()}}";
   const ctx = document.getElementById('myChart');

  $(document).ready(function(){
  showGraficoBarraCitasMes();
  });
  /*OBTENER LA DATA DE LAS CITAS MEDICAS POR MES EN GRAFICO DE BARRAS*/
  function showGraficoBarraCitasMes()
  {
    let Cantidad = [];
    $.ajax({
      url:URL+"citas_medicas_por_mes",
      method:"GET",
      success:function(response){
        response = JSON.parse(response);
          
        
        response.response.forEach(resp => {
           Cantidad.push(resp.cantidad)
        });
        
    new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio','Julio','Agosto','Setiembre','Octubre','Noviembre','Diciembre'],
      datasets: [{
        label: 'Citas finalizados (2023)',
        data:Cantidad,
        borderWidth: 3,
        backgroundColor: [
          "rgb(0, 191, 255)",
          "rgb(0, 191, 255)",
          "rgb(178, 34, 34)"
        ]
         
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
      
    }
  });
      }
    });
  }
</script>
@endsection
 