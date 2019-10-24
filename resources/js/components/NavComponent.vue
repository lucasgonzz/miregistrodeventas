<template>
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
                    <li class="nav-item">
                        <a class="nav-link nav-link-active" :href="vender">
                            <i class="icon-tag"></i>
                            Vender
                        </a>
                    </li>
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
                           Lucas
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="">
                               Salir
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
export default {
    props: [ 'vender', 'nuevo', 'listado' ],
}
</script>