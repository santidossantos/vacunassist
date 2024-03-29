<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ver Turnos</title>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
        <link href="assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/libs/css/style.css">
        <link rel="stylesheet" href="assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
        <link rel="stylesheet" href="assets/vendor/charts/chartist-bundle/chartist.css">
        <link rel="stylesheet" href="assets/vendor/charts/morris-bundle/morris.css">
        <link rel="stylesheet" href="assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="assets/vendor/charts/c3charts/c3.css">
        <link rel="stylesheet" href="assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
    </head>

    <body>



        <div class="dashboard-main-wrapper">

            <div class="dashboard-header">
                <nav class="fixed-top">
                    @include('partials.nav')
                </nav>
            </div>

        @include('partials.menu')

        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <h2 class="pageheader-title">Gestión de Turnos</h2>
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Home</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Ver Turnos</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ecommerce-widget">

                <div class="row">
        <div class="col-xl-9 col-lg-8 col-md-11 col-sm-12 col-2">
            <div class="card">
                <h5 class="card-header">Mis turnos</h5>
                <div class="card-body">
                <div class="container">
  <div class="row">
    <div class="col-12">
        <?php
            $id_usuario=auth()->id();
            $turnos = DB::table('turnos')
            ->select('turnos.id AS id_turno' , 'turnos.id_vacuna' ,
            'turnos.fecha' , 'turnos.id_zona' , 'vacunas.nombreVacuna'
             , 'zonas.nombreZona' , 'turnos.estado')
            ->join('vacunas', 'vacunas.id', '=', 'turnos.id_vacuna')
            ->join('zonas', 'zonas.id', '=', 'turnos.id_zona')
            ->where('id_paciente', $id_usuario)
                ->where(function($q){
                    $q->where('turnos.estado', 'pendiente')
                    ->orWhere('turnos.estado', 'aprobacion');
                })
            ->get();
        ?>

    @if ($turnos->count())
      <table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">Vacuna</th>
            <th scope="col">Fecha del turno</th>
            <th scope="col">Zona</th>
            <th scope="col">Estado</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($turnos as $turno)
        <tr>
            <td>{{ $turno->nombreVacuna}}</td>
            <td>{{ $turno->fecha}}</td>
            <td>{{ $turno->nombreZona}}</td>
            @if ($turno->estado == 'pendiente')
                <td><span class="badge-dot badge-success mr-1"></span> Pendiente</td>
            @elseif ($turno->estado == 'aprobacion')
                <td><span class="badge-dot badge-brand mr-1"></span> Pendiente para Aprobación</td>
            @endif

            <td>
                <form action="{{ route('turnos.edit', ['id'=>$turno->id_turno]) }}" method="get" class="formulario-eliminar">
                    <button type="submit" class="btn btn-danger">
                    <i class="far fa-trash-alt"></i> Cancelar turno </button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
      </table>
      @else
      <td><span class="badge-dot badge-brand mr-1"></span> No hay turnos para mostrar</td>
      @endif
    </div>
  </div>
</div>
                    </div>
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>
        </div>
        @include('partials.footer')
        </div>
    </body>
    @if (session('eliminar') == 'ok')
    <script>
        Swal.fire(
        'Cancelado!',
        'El turno se cancelo correctamente.',
        'success'
    )
    </script>
@endif
    <script>

        $('.formulario-eliminar').submit(function(e){
            e.preventDefault();
            Swal.fire({
  title: '¿Está seguro de cancelar el turno?',
  text: "el turno se cancelara definitivamente",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Aceptar',
  cancelButtonText: 'Cancelar'
}).then((result) => {
  if (result.isConfirmed) {
    this.submit();
  }
})
        });
    </script>
@if (session('solicitar') == 'ok')
    <script>
        Swal.fire(
        'Turno exitoso!',
        'Se generó el turno correctamente.',
        'success'
    )
    </script>
@endif

@if (session('ninguna') == 'ok')
    <script>
        Swal.fire(
        'Turno exitoso!',
        'Se generó el turno correctamente para la primer dosis de covid.',
        'success'
    )
    </script>
@endif

@if (session('una_dosis') == 'ok')
    <script>
        Swal.fire(
        'Turno exitoso!',
        'Se generó el turno correctamente para la segunda dosis de covid.',
        'success'
    )
    </script>
@endif



</html>
