@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="" method="POST">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-7">
                                    <h5>Registrar vendedor</h5>
                                </div>
                                <div class="col-sm-5">
                                    <div class="float-right">
                                        <a href="{{ url('users') }}" class="btn btn-sm btn-secondary">
                                            <i class="fa fa-rotate-left"></i>
                                            Regresar
                                        </a>
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fa fa-save"></i>
                                            Registrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5><i class="fa fa-navicon"></i> Datos de la cuenta</h5>
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" placeholder="" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" class="form-control" id="email" placeholder="" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="password" placeholder="" name="password" value="{{ old('password') }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
@endsection
