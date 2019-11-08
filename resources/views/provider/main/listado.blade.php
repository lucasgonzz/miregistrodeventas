@extends('layouts.app')

@section('content')
<!-- {{ Auth()->user()->hasRole('commerce') }} -->
<listado rol="provider"></listado>
@endsection

