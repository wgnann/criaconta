@extends('layouts.app')

@section ('content')
    <div class="card card-outline card-primary">
        <div class="card-header text-white bg-info">
            <h5 class="card-title">Criar conta pessoal</h5>
        </div>
        <div class="card-body">
            @include('account.partials.personal')
        </div>
    </div>
@endsection
