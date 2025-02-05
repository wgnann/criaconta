@extends('layouts.app')

@section ('content')
    <div class="card card-outline card-primary">
        <div class="card-header text-white bg-info">
            <h5 class="card-title">Criar conta local</h5>
        </div>
        <div class="card-body">
            <p>A conta local destina-se aos casos onde não há interesse em manter uma conta de email <span class="font-weight-light">@ime.usp.br</span>.
            @include('local.partials.form')
        </div>
    </div>
@endsection
