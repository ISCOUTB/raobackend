@extends('layouts.admin')

@section('title', 'Administrar Periodos')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h3>Aplicaciones con acceso</h3>
    </div>
    <div class="col-md-8 col-md-offset-2">
        <a class="btn btn-primary" href="/admin/apps/create">Crear aplicación</a>
    </div>
    <div class="col-md-8 col-md-offset-2">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Hash</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if($apps)
                    @foreach ($apps as $app)
                    <tr>
                        <td>{{ $app->id }}</td>
                        <td>{{ $app->name }}</td>
                        <td>{{ $app->hash }}</td>
                        <td class="text-center">
                            <a class="btn btn-primary btn-xs" href="/admin/apps/{{$app->id}}/edit" ><span class="glyphicon glyphicon-pencil"></span></a>
                            <a class="btn btn-danger btn-xs" href="/admin/apps/{{$app->id}}/destroy"><span class="glyphicon glyphicon-trash"></span></a>
                            <a class="btn btn-warning btn-xs" href="/admin/apps/{{$app->id}}/refresh-hash"><span class="glyphicon glyphicon-repeat"></span></a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection