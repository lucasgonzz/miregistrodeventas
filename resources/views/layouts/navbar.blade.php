<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
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
                    <a class="nav-link nav-link-active" href="{{ route('vender.provider') }}">
                        <i class="icon-tag"></i>
                        Vender
                    </a>
                </li>
                @endif
                @if (Auth()->user()->hasRole('commerce'))
                <li class="nav-item">
                    <a class="nav-link nav-link-active" href="{{ route('vender.commerce') }}">
                        <i class="icon-tag"></i>
                        Vender
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" :href="nuevo">
                        <i class="icon-plus"></i>
                        Nuevo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :href="listado">
                        <i class="icon-list-ol"></i>
                        Listado
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="icon-clipboard-2"></i>
                        Ventas
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