<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm p-b-0 p-t-5">
    <div class="container">
        <a class="navbar-brand" href="">
            <!-- {{ config('app.name', '') }} -->
            Mi resumen de ventas
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @if (Auth()->user()->hasRole('provider'))
                <li class="nav-item">
                    <a class="nav-link {{ active('mayoristas/vender') }}" href="{{ route('vender.provider') }}">
                        <!-- <i class="icon-tag"></i> -->
                        Vender
                    </a>
                </li>
                @endif
                @if (Auth()->user()->hasRole('commerce'))
                <li class="nav-item">
                    <a class="nav-link {{ active('comercios/vender') }}" href="{{ route('vender.commerce') }}">
                        <!-- <i class="icon-tag"></i> -->
                        Vender
                    </a>
                </li>
                @endif
                @if (Auth()->user()->hasRole('provider'))
                <li class="nav-item">
                    <a class="nav-link {{ active('mayoristas/ingresar') }}" href="{{ route('ingresar.provider') }}">
                        <!-- <i class="icon-plus"></i> -->
                        Ingresar
                    </a>
                </li>
                @endif
                @if (Auth()->user()->hasRole('commerce'))
                <li class="nav-item">
                    <a class="nav-link {{ active('comercios/ingresar') }}" href="{{ route('ingresar.commerce') }}">
                        <!-- <i class="icon-plus"></i> -->
                        Ingresar
                    </a>
                </li>
                @endif
                @if (Auth()->user()->hasRole('provider'))
                <li class="nav-item">
                    <a class="nav-link {{ active('mayoristas/listado') }}" href="{{ route('listado.provider') }}">
                        <!-- <i class="icon-list-ol"></i> -->
                        Listado
                    </a>
                </li>
                @endif
                @if (Auth()->user()->hasRole('commerce'))
                <li class="nav-item">
                    <a class="nav-link {{ active('comercios/listado') }}" href="{{ route('listado.commerce') }}">
                        <!-- <i class="icon-list-ol"></i> -->
                        Listado
                    </a>
                </li>
                @endif
                @if (Auth()->user()->hasRole('provider'))
                <li class="nav-item">
                    <a class="nav-link {{ active('mayoristas/ventas') }}" href="{{ route('ventas.provider') }}">
                        <!-- <i class="icon-list-ol"></i> -->
                        Ventas
                    </a>
                </li>
                @endif
                @if (Auth()->user()->hasRole('commerce'))
                <li class="nav-item">
                    <a class="nav-link {{ active('comercios/ventas') }}" href="{{ route('ventas.commerce') }}">
                        <!-- <i class="icon-list-ol"></i> -->
                        Ventas
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ active('codigos-de-barra') }}" href="{{ route('bar-codes') }}">
                        <!-- <i class="icon-list-ol"></i> -->
                        Codigos de barra
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ active('empleados') }}" href="{{ route('empleados') }}">
                        <!-- <i class="icon-list-ol"></i> -->
                        Empleados
                    </a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                       {{ Auth()->user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a href="{{ route('commerce.config') }}" class="dropdown-item">                            
                           Configuración
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            {{ csrf_field() }}
                            <button class="dropdown-item" type="submit">                            
                               Salir
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>