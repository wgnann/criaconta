@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('content')
  @include('flash')
  @auth
    <div class="panel">
      <h4>Sistema de criação de contas</h4>
      <p>Utilize o menu acima.</p>

      <ul>
        <li>Conta pessoal: conta individual. Precisa de email <span class="font-weight-light">@ime.usp.br</span>.</li>
        @can ('institutional')
          <li>Conta institucional: conta não-pessoal. Precisa de email <span class="font-weight-light">@ime.usp.br</span>.</li>
          <li>Conta local: conta individual temporária destinada para visitantes.<br/> A SI recomenda proceder com o pedido de Número USP para a CPQ em vez de criar uma conta local.</li>
        @endcan
      </ul>

      @can ('institutional')
        <p>Mais informações disponíveis em: <a href="https://wiki.ime.usp.br/servicos:contas">https://wiki.ime.usp.br/servicos:contas</a>.</p>
      @endcan
    </div>
  @else
    <div class="panel">
      <p>
      Estudante de pós-graduação: ter um email IME é requisito <strong>necessário</strong> para fazer sua conta para o laboratório da Rede IME.
      </p>
      <p>O email pode ser criado em: <a href="https://id.usp.br">id.usp.br</a>.
      </p>
      </p>
      <p>
      Faça o <a href="/login">login</a>!
      </p>
    </div>
  @endauth
@endsection

@section ('footer')
@endsection

