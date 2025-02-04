@extends('layouts.app')

@section ('content')
    <div class="panel">
        <h4>Sistema de criação de contas</h4>
        @auth
            <p>Utilize o menu acima.</p>
            <ul>
                <li><a href="{{ route('account') }}">Conta pessoal</a>: conta individual. Precisa de email <span class="font-weight-light">@ime.usp.br</span>.</li>
                @can ('institutional')
                    <li><a href="{{ route('institutional.account') }}">Conta institucional</a>: conta não-pessoal. Precisa de email <span class="font-weight-light">@ime.usp.br</span>.</li>
                    <li><a href="{{ route('local.account') }}">Conta local</a>: conta individual temporária destinada para visitantes.<br/> A SI recomenda proceder com o pedido de Número USP para a CPQ em vez de criar uma conta local.</li>
                @endcan
            </ul>

            @can ('institutional')
                <p>Mais informações disponíveis em: <a href="https://wiki.ime.usp.br/servicos:contas">https://wiki.ime.usp.br/servicos:contas</a>.</p>
            @endcan
        @else
            <p>
            Estudante de pós-graduação: ter um email IME é requisito <strong>necessário</strong> para fazer sua conta para o laboratório da Rede IME.
            </p>
            <p>O email pode ser criado em: <a href="https://id.usp.br">id.usp.br</a>.</p>
            <p>Faça o <a href="/login">login</a>!</p>
        @endauth
    </div>
@endsection
