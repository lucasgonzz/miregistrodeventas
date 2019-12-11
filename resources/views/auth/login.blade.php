<!DOCTYPE html>
<html lang="en">
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
    
</body>
</html>
<div class="container">
    <div class="row justify-content-center m-t-30">
        <div class="col col-lg-4">
            @if(session('errorLogin'))
                <div class="alert alert-danger">
                    <p>{{ session('errorLogin') }}</p>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h1 class="h3">Ingresar</h1>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">Usuario</label>
                            <input type="text" name="name" id="name" placeholder="Nombre de usuario" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password" placeholder="Contraseña" class="form-control">
                        </div>
                        <button class="btn btn-primary btn-lg btn-block">Ingresar</button>
                    </form>
                    <a href="{{ route('show-register-form') }}" class="btn btn-link">Registrarme</a>
                </div>
            </div>
        </div>
    </div>
</div>
