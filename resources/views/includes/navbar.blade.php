<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @if(Auth::check())
                    <li class="nav-item {{ request()->is('home') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('home') }}">Dashboard <span class="sr-only">(current)</span></a>
                    </li>
                    @if(auth()->user()->is_role(1))
                    <li class="nav-item {{ request()->is('lotteries*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('lotteries') }}">Loterías</a>
                    </li>
                    @endif
                    <li class="nav-item {{ request()->is('tickets*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('tickets/create') }}">Tickets</a>
                    </li>
                    <li class="nav-item {{ request()->is('*sales') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('report/sales') }}">Ventas por rango</a>
                    </li>
                    <li class="nav-item {{ request()->is('*winners') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('winners') }}">Números ganadores</a>
                    </li>
                    @if(auth()->user()->is_role(1))
                        <li class="nav-item {{ request()->is('sales-limit*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('sales-limit') }}">Límite de ventas</a>
                        </li>
                        <li class="nav-item {{ request()->is('raffles*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('raffles') }}">Sorteos</a>
                        </li>
                    @endif
                    <li class="nav-item {{ request()->is('*balance_sheets') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('balance_sheets') }}">Balance de cuenta</a>
                    </li>
                @endif
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Ingresar</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                Salir
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
