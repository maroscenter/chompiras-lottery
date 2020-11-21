@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-3">
            <div class="card-header">
                Dashboard
            </div>
            <div class="card-body">
                <p>Bienvenido, {{ auth()->user()->name }}</p>

                <p>Seleccione una de las siguientes opciones:</p>
                <form class="form-inline" role="search">
                    <div class="form-row">
                        <div class="col">
                            <select name="user_id" id="" class="form-control">
                                <option value="">Seleccione una opción</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == $userId ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <select name="lottery_id" id="" class="form-control">
                                <option value="">Seleccione una opción</option>
                                @foreach($lotteries as $lottery)
                                    <option value="{{ $lottery->id }}" {{ $lottery->id == $lotteryId ? 'selected' : '' }}>{{ $lottery->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" name="date" value="{{ $date }}">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                </form>
                {{--<div class="row text-center">--}}
                {{--<div class="col-md-4">--}}

                {{--<a href="/users" class="btn btn-primary">--}}
                {{--Consultar tickets por vendedor--}}
                {{--<span class="glyphicon glyphicon-user"></span>--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                {{--<a href="/lotteries" class="btn btn-primary">--}}
                {{--Consultar tickets por lotería--}}
                {{--<span class="glyphicon glyphicon-star"></span>--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                {{--<a href="/dates" class="btn btn-primary">--}}
                {{--Consultar tickets por fecha--}}
                {{--<span class="glyphicon glyphicon-calendar"></span>--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Tickets vendidos
            </div>
            <div class="card-body">
                <p>A continuación, un listado de las últimos tickets vendidos.</p>

                @include('includes.sold_tickets')
            </div>
        </div>
    </div>
@endsection
