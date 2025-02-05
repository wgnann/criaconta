@extends('layouts.app')

@section ('content')
    @include('laravel-usp-theme::blocos.datatable-simples')
    <div class="card card-outline card-primary">
        <div class="card-header text-white bg-info">
            <h5 class="card-title">Contas de {{ Auth::user()->name }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive-sm">
                @include('account.partials.table')
            </div>
        </div>
    </div>
@endsection
