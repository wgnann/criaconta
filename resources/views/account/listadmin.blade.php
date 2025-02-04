@extends('layouts.app')

@section ('content')
    @include('laravel-usp-theme::blocos.datatable-simples')
    <div class="panel">
        <div class="card card-outline card-primary">
            <div class="card-header text-white bg-primary">
                @if ($search)
                    <h5 class="card-title">Filtrando por: {{ $search }}</h5>
                @else
                    <h5 class="card-title">Todas as contas</h5>
                @endif
            </div>
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('account.listadmin') }}" method="get">
                            <div class="form-group input-group">
                                <input class="form-control" type="text" name="search" placeholder="Filtrar por login ou número USP" />
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card border-0">
                    <div class="card-body">
                        @include('account.partials.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
