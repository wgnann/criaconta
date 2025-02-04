@extends('layouts.app')

@section ('content')
    <div class="card card-outline card-primary">
        @if ($account->ativo)
            <div class="card-header text-white bg-success">
                <h5 class="card-title">{{ $account->username }}</h5>
            </div>
        @else
            <div class="card-header text-white bg-danger">
                <h5 class="card-title">Conta em criação (demora até 5min)</h5>
            </div>
        @endif
        <div class="card-body">
            <div class="row">
                <div class="col">
                    @include('account.partials.owner')
                </div>
                <div class="col">
                    @include('account.partials.info')
                </div>
            </div>
        </div>
    </div>
@endsection
