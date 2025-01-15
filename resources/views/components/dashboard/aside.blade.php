 
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme @yield('clase_ocultar')">
  <div class="app-brand demo">
  <a href="index.html" class="app-brand-link">
  <img src="public/asset/img/favicon/utpl.png" alt="Logo UTPL" style="width: 120px; height: auto;">
</a>


    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>
    
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
    {{--- Dashboard del sistema(inicial del sistema)---}}
      <li class="menu-item active">
        <a href="{{$this->route('dashboard')}}" class="menu-link text-white">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Analytics" style="color: #666464">Dashboard 
          </div>
        </a>
      </li>
      @if ($this->authenticado() and ($this->profile()->rol === 'Director' or $this->profile()->rol=== 'Admisión' or
      $this->profile()->rol=== 'Enfremera-Triaje' ))
      <li class="menu-item">
        <a href="{{$this->route('escritorio')}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/desktop.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra" style="color: #000000"><b>Escritorio</b></div>
        </a>
      </li>
      @endif
      <!-- Configuración del sistema -->
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <img src="{{$this->asset('img/icons/unicons/config.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Layouts" class="letra" style="color: #0f0606"><b>Configuración </b></div>
        </a>
      
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{$this->route('profile/editar')}}" class="menu-link">
              <div data-i18n="Without navbar" class="letra" >Actualizar perfíl</div>
            </a>
          </li>
          @if ($this->authenticado())
              @if ($this->profile()->rol === 'Director')
              <li class="menu-item">
                <a href="{{$this->route('/Configurar-datos-empresa-EsSalud')}}" class="menu-link">
                  <div data-i18n="Container" class="letra">Datos de Hospital Isidro Ayora Loja</div>
                </a>  </li>
              @endif
          @endif
        </ul>
      </li>

      {{-- VER MI INFORME MÉDICO ---}}

      @if ($this->authenticado() and $this->profile()->rol === 'Paciente')
      <li class="menu-item">
        <a href="{{$this->route("paciente/consultar_informe_medico")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/informe_medico.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra text-dark"><b style="color: #000000">Informe médico</b></div>
        </a>
      </li>
      @endif
     
      <!-- Tipo de documentos -->
      @if ($this->authenticado() and $this->profile()->rol === 'Director')
      <li class="menu-item">
        <a href="{{$this->route("user_gestion")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/user.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra text-dark"><b style="color: #080404">Gestionar usuarios</b></div>
        </a>
      </li>
      @endif

      @if ($this->authenticado() and $this->profile()->rol === 'Director')
      <li class="menu-item">
        <a href="{{$this->route("tipo-documentos-existentes")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/documento.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra text-dark"><b style="color: #030101">Cédula de identidad</b></div>
        </a>
      </li>
      @endif

      @if ($this->authenticado() and $this->profile()->rol === 'Director')
      <li class="menu-item">
        <a href="{{$this->route("departamentos")}}" class="menu-link">
          <i class="menu-icon tf-icons bx bxs-city"></i>
          <div data-i18n="Analytics" class="letra text-dark"><b style="color: #010101">Departamentos</b></div>
        </a>
      </li>
      @endif

      @if ($this->authenticado() and ($this->profile()->rol === 'Director' or $this->profile()->rol === 'Admisión'))
      <li class="menu-item">
        <a href="{{$this->route("paciente")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/paciente.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra text-dark"><b style="color: #0b0606">Pacientes</b></div>
        </a>
      </li>
      @endif

      @if ($this->authenticado() and ($this->profile()->rol === 'Director' || $this->profile()->rol === 'Médico'))
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <img src="{{$this->asset('img/icons/unicons/medico.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Layouts" class="letra text-dark"><b style="color: #010000">Médico</b></div>
        </a>
      
        <ul class="menu-sub">
          @if ($this->authenticado() and $this->profile()->rol === 'Director')
          <li class="menu-item">
            <a href="{{$this->route("medicos")}}" class="menu-link">

              {{-- SOLO ADMINISTARDORES--}}
              <div data-i18n="Without menu" class="letra" style="color: ##696969">Gestionar médicos</div>
            </a>
          </li>
          @endif
          {{---SOLO PARA LOS MÉDICOS --}}
          @if ($this->profile()->rol === 'Médico')
          <li class="menu-item">
            <a href="{{str_replace(" ","_",$this->route($this->profile()->name).'/horarios')}}" class="menu-link">
              <div data-i18n="Without navbar" class="letra">mis horarios</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{$this->route("medico/import-dias-de-atencion")}}" class="menu-link">
              <div data-i18n="Without navbar" class="letra">Dias de atención</div>
            </a>
          </li>
          @endif
          
       
        </ul>
      </li>
      @endif
      @if ($this->profile()->rol === 'Paciente' and isset($this->profile()->id_persona))
      <li class="menu-item">
       <a href="{{$this->route('seleccionar-especialidad')}}" class="menu-link">
        <img src="{{$this->asset('img/icons/unicons/ctma.ico')}}" class="menu-icon" alt="">
         <div data-i18n="Without menu" class="letra"><b style="color: #0b0606">Sacar cita</b></div>
       </a>
     </li>
      @endif
      @if ($this->profile()->rol === 'Paciente' and isset($this->profile()->id_persona))
      <li class="menu-item">
       <a href="{{$this->route('citas-realizados')}}" class="menu-link">
        <img src="{{$this->asset('img/icons/unicons/citas_save.ico')}}" class="menu-icon" alt="">
         <div data-i18n="Without menu" class="letra"><b style="color: #0b0606">Citas realizados</b></div>
       </a>
     </li>
      @endif
      @if ($this->authenticado())
          @if ($this->profile()->rol === 'Admisión' || $this->profile()->rol === 'Director')
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <img src="{{$this->asset('img/icons/unicons/clinica.ico')}}" class="menu-icon" alt="">
              <div data-i18n="Layouts" class="letra text-dark"><b style="color: #060404">Cita médica</b></div>
            </a>
          
            <ul class="menu-sub">
    
              @if ($this->authenticado())
    
               @if ($this->profile()->rol === 'Admisión')
               <li class="menu-item">
                <a href="{{$this->route('crear-nueva-cita-medica')}}" class="menu-link">
    
                  <div data-i18n="Without menu" class="letra">nueva cita médica</div>
                </a>
              </li>
               @endif
                  
              @endif
    
              @if ($this->authenticado())
    
               @if ($this->profile()->rol === 'Admisión' || $this->profile()->rol === 'Director')
               <li class="menu-item">
                <a href="{{$this->route("citas-programados")}}" class="menu-link">
                  <div data-i18n="Without navbar" class="letra">citas programados</div>
                </a>
              </li>
               @endif
                  
              @endif
           
            </ul>
          </li>
          @endif
      @endif
      
      @if ($this->profile()->rol === 'Enfermera-Triaje' or $this->profile()->rol === 'Médico')
      <li class="menu-item">
        <a href="{{$this->route("triaje/pacientes")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/triaje.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra"><b style="color: #191717">Pacientes (Triaje)</b></div>
        </a>
      </li>
      @endif
      
      @if ($this->profile()->rol === 'Médico')
      <li class="menu-item">
        <a href="{{$this->route("nueva_atencion_medica")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/atencion_medica.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra"><b style="color:#070606">Atención médica</b></div>
        </a>
      </li>
      @endif

      
      <li class="menu-item">
        <a href="{{$this->route("triaje/pacientes")}}" class="menu-link">
          <img src="{{$this->asset('img/icons/unicons/informe.ico')}}" class="menu-icon" alt="">
          <div data-i18n="Analytics" class="letra"><b style="color:#0a0909">Reportes</b></div>
        </a>
      </li>
      
       
    </ul>
  </aside>