<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm p-b-0 p-t-5">
    <div class="container">
        <a class="navbar-brand" href="">
            <!-- {{ config('app.name', '') }} -->
            Mi registro de ventas
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @if (Auth()->user()->hasRole('provider'))
                    @if (Auth()->user()->hasPermissionTo('sale.create'))
                    <li class="nav-item">
                        <a class="nav-link {{ active('mayoristas/vender') }}" href="{{ route('vender.provider') }}">
                            <!-- <i class="icon-tag"></i> -->
                            Vender
                        </a>
                    </li>
                    @endif
                    @if (Auth()->user()->hasPermissionTo('article.create'))
                    <li class="nav-item">
                        <a class="nav-link {{ active('mayoristas/ingresar') }}" href="{{ route('ingresar.provider') }}">
                            <!-- <i class="icon-plus"></i> -->
                            Ingresar
                        </a>
                    </li>
                    @endif
                    @if (Auth()->user()->hasPermissionTo('article.index'))
                    <li class="nav-item">
                        <a class="nav-link {{ active('mayoristas/listado') }}" href="{{ route('listado.provider') }}">
                            <!-- <i class="icon-list-ol"></i> -->
                            Listado
                        </a>
                    </li>
                    @endif
                    @if (Auth()->user()->hasPermissionTo('sale.index'))
                    <li class="nav-item">
                        <a class="nav-link {{ active('mayoristas/ventas') }}" href="{{ route('ventas.provider') }}">
                            <!-- <i class="icon-list-ol"></i> -->
                            Ventas
                        </a>
                    </li>
                    @endif
                    @if (Auth()->user()->hasPermissionTo('bar_code.create'))
                    <li class="nav-item">
                        <a class="nav-link {{ active('mayoristas/codigos-de-barra') }}" href="{{ route('bar-codes.provider') }}">
                            <!-- <i class="icon-list-ol"></i> -->
                            Codigos de barra
                        </a>
                    </li>
                    @endif
                    @if (Auth()->user()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link {{ active('mayoristas/empleados') }}" href="{{ route('empleados.provider') }}">
                            <!-- <i class="icon-list-ol"></i> -->
                            Empleados
                        </a>
                    </li>
                    @endif
                @endif
                @if (Auth()->user()->hasRole('commerce'))
                    @if (Auth()->user()->hasPermissionTo('sale.create'))
                    <li class="nav-item">
                        <a class="nav-link {{ active('comercios/vender') }}" href="{{ route('vender.commerce') }}">
                            <!-- <i class="icon-tag"></i> -->
                            Vender
                        </a>
                    </li>
                    @endif
                    @if (Auth()->user()->hasPermissionTo('article.create'))
                    <li class="nav-item">
                        <a class="nav-link {{ active('comercios/ingresar') }}" href="{{ route('ingresar.commerce') }}">
                            <!-- <i class="icon-plus"></i> -->
                            Ingresar
                        </a>
                    </li>
                    @endif
                    @if (Auth()->user()->hasPermissionTo('article.index'))
                    <li class="nav-item">
                        <a class="nav-link {{ active('comercios/listado') }}" href="{{ route('listado.commerce') }}">
                            <!-- <i class="icon-list-ol"></i> -->
                            Listado
                        </a>
                    </li>
                    @endif
                    @if (Auth()->user()->hasPermissionTo('sale.index'))
                    <li class="nav-item">
                        <a class="nav-link {{ active('comercios/ventas') }}" href="{{ route('ventas.commerce') }}">
                            <!-- <i class="icon-list-ol"></i> -->
                            Ventas
                        </a>
                    </li>
                    @endif
                    @if (Auth()->user()->hasPermissionTo('bar_code.create'))
                    <li class="nav-item">
                        <a class="nav-link {{ active('comercios/codigos-de-barra') }}" href="{{ route('bar-codes.commerce') }}">
                            <!-- <i class="icon-list-ol"></i> -->
                            Codigos de barra
                        </a>
                    </li>
                    @endif
                    @if (Auth()->user()->hasRole('admin'))
                    <li class="nav-item">
                        <a class="nav-link {{ active('comercios/empleados') }}" href="{{ route('empleados.commerce') }}">
                            <!-- <i class="icon-list-ol"></i> -->
                            Empleados
                        </a>
                    </li>
                    @endif
                @endif
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                       {{ Auth()->user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <!-- <a href="{{ route('commerce.config') }}" class="dropdown-item">                            
                           Configuración
                        </a> -->
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