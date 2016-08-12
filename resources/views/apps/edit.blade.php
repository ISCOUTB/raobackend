@extends('layouts.admin')

@section('title', 'Administrar Periodos')

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="form-area">
            <form method="post" action="update">
                <div class="form-group">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nombre" value="{{$app->name}}" required>
                </div>
                <input type="submit" class="btn btn-primary pull-right" value="Guardar">
            </form>
        </div>
    </div>
</div>
@endsection