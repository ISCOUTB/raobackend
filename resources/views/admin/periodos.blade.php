@extends('layouts.admin')

@section('title', 'Administrar Periodos')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h2>Períodos</h2>
            <a href="/admin/periodos/create" class="btn btn-primary">Agregar</a>
            <br><br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Periodo</th>
                        <th>Activo</th>
                        <th>Funciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($periodos as $periodo)
                    <tr>
                        <th>
                            {{$periodo->periodo}}
                        </th>
                        <th>
                            {{$periodo->active}}
                        </th>
                        <th>
                            <a href="periodos/{{$periodo->id}}/edit">Editar</a>
                        </th>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

