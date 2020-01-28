<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Control de Stock</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- app.css -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- toastr.css -->
    <link href="{{ asset('css/toastr.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center m-t-30">
            <div class="col col-lg-4">
                @if(session('message'))
                    <div class="alert alert-danger">
                        <p>{{ session('message') }}</p>
                    </div>
                @endif
                @if(session('status'))
                    <div class="alert alert-success">
                        <p>{{ session('status') }}</p>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3">Cambiar mi contraseña</h1>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="name">Contraseña actual</label>
                                <input type="password" name="mypassword" id="password" placeholder="Contraseña actual" class="form-control">
                                <div class="text-danger">{{ $errors->first('mypassword') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="password">Nueva contraseña</label>
                                <input type="password" name="password" id="password" placeholder="Nueva contraseña" class="form-control">
                                <div class="text-danger">{{ $errors->first('password') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="password">Confirme la nueva contraseña</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Nueva contraseña" class="form-control">
                                <div class="text-danger">{{ $errors->first('password_confirmation') }}</div>
                            </div>
                            <button class="btn btn-primary btn-lg btn-block">
                                Cambiar contraseña
                            </button>
                            <a href="{{ url('/') }}" class="btn btn-primary btn-lg btn-block">
                                Volver atras
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="https://kit.fontawesome.com/6a9cf36c74.js"></script>
</body>
</html>

