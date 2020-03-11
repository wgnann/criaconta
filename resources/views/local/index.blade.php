@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('styles')
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('/vendor/laravel-usp-theme/css/style.css')}}">
@endsection

@section ('content')
  <h4>Informações das contas locais</h4>
  <p>A conta local destina-se aos casos onde não há interesse em manter uma conta de email <span class="font-weight-light">@ime.usp.br</span>.
  <div class="border border-info my-1 p-1">
    <h5 class="text-info">Criar conta local</h5>
    <form action="{{ route('local.accounts') }}" method="post">
      @csrf
      <div class="form-row">
        <div class="form-group col">
          <label for="username">Login</label>
          <div class="input-group">
            <input class="form-control" type="text" id="username" name="username" placeholder="usuário" pattern="[A-Za-z0-9]*" title="Apenas letras ou números"/>
            <div class="input-group-append">
              <span class="input-group-text">-local</span>
            </div>
          </div>
        </div>
        <div class="form-group col">
          <label for="name">Nome</label>
          <input class="form-control" type="text" id="name" name="name" placeholder="Nome completo" />
        </div>
        <div class="form-group col">
          <label for="group">Grupo</label>
          <select class="custom-select" id="group" name="group">
            <option select>Selecionar grupo</option>
            @foreach ($groups as $group)
              <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group input-group">
        <textarea class="form-control" name="obs" placeholder="Observações"></textarea>
        <button class="btn btn-info" type="submit">Criar</button>
      </div>
    </form>
  </div>
  @if ($accounts)
    <div class="border border-success my-1 p-1">
      <h5 class="text-success">Contas solicitadas</h5>
      <table class="table table-sm table-borderless">
        <thead>
          <tr>
            <th>Login</th>
            <th>Nome</th>
            <th>Status</th>
          </td>
        </thead>
        <tbody>
          @foreach ($accounts as $account)
            <tr>
              <td>{{ $account->username }}</td>
              <td>{{ $account->name }}</td>
              <td>
                @if ($account->ativo == 1)
                  ativo
                @else
                  inativo
                @endif
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
@endsection

@section ('footer')
@endsection

