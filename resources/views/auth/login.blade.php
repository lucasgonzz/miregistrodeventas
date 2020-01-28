<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingresar</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Icons -->
    <link href="{{ asset('styles.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center m-t-30">
            <div class="col col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3">Ingresar</h1>
                    </div>
                    <div class="card-body">
                        @if(session('errorLogin'))
                            <div class="alert alert-danger">
                                <p>{{ session('errorLogin') }}</p>
                            </div>
                        @endif
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" 
                                    id="nav-owner-tab" 
                                    data-toggle="tab" 
                                    href="#nav-owner" 
                                    role="tab" 
                                    aria-controls="nav-owner" 
                                    aria-selected="true">
                                    Dueño
                                </a>
                                <a class="nav-item nav-link" 
                                    id="nav-employee-tab" 
                                    data-toggle="tab" 
                                    href="#nav-employee" 
                                    role="tab" 
                                    aria-controls="nav-employee" 
                                    aria-selected="false">
                                    Empleado
                                </a>
                            </div>
                        </nav>
                        <div class="tab-content p-t-10" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-owner" role="tabpanel" aria-labelledby="nav-owner-tab">
                                <form method="GET" 
                                        id="login-owner-form"
                                        action="{{ route('login-owner') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="name">Usuario</label>
                                        <input type="text" name="name" id="name" placeholder="Nombre de usuario" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Contraseña</label>
                                        <input type="password" name="password" id="password" placeholder="Contraseña" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        Ingresar
                                    </button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="nav-employee" role="tabpanel" aria-labelledby="nav-employee-tab">
                                <form method="POST" 
                                    action="{{ route('login-employee') }}"
                                    >
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="commerce-name">Comercio</label>
                                        <input type="text" name="commerce_name" id="commerce-name" placeholder="Nombre del comercio" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Usuario</label>
                                        <input type="text" name="employee_name" 
                                                id="name" 
                                                placeholder="Nombre de usuario" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="employee_password">Contraseña</label>
                                        <input type="password" name="employee_password" id="employee_password" placeholder="Contraseña" class="form-control">
                                    </div>
                                    <button class="btn btn-primary btn-lg btn-block">
                                        Ingresar
                                    </button>
                                </form>
                            </div>
                        </div>
                        <!-- <a href="{{ route('show-register-form') }}" class="btn btn-link">Registrarme</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

