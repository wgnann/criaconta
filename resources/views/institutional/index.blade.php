@extends('layouts.app')

@section ('content')
    <div class="card card-outline card-primary">
        <div class="card-header text-white bg-info">
            <h5 class="card-title">Criar conta institucional</h5>
        </div>
        <div class="card-body">
            <p>A conta institucional destina-se aos casos como seções e eventos que já contam com um endereço de email <span class="font-weight-light">@ime.usp.br</span>.
            @if ($todo)
                @include('institutional.partials.form')
            @else
                <p>Crie seu endereço email institucional em: <a href="https://id.usp.br">id.usp.br</a>.</p>
            @endif
        </div>
    </div>
@endsection
