@extends($this->Layouts("plantilla_home"))

@section("title_home","Hospital Isidro Ayora Loja")


@section('contenido')
  
  @include($this->getComponents("home.header"))

  @include($this->getComponents("home.section"))

  @include($this->getComponents("home.main"))

  @include($this->getComponents("home.footer"))



@endsection
 
 