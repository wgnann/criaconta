@extends ('laravel-usp-theme::master')

@section ('content')
  @include('messages.errors')
  <div class="panel">
    <h4>Conta: {{ $account->username }}</h4>
    @if ($account->ativo)
      <div class="border border-success my-1 p-1">
        <h5 class="text-success">Responsável</h5>
    @else
      <div class="border border-danger my-1 p-1">
        <h5 class="text-danger">Conta em criação (demora até 5min)</h5>
    @endif
        <table class="table table-sm table-borderless">
          <tr>
            <td><h5>Responsável</h5></td>
          </tr>
          <tr>
            <th>Número USP</th>
            <td>{{ $account->user->codpes }}</td>
          </tr>
          <tr>
            <th>Nome</th>
          <td>{{ $account->user->name }}</td>
          </tr>
          <tr>
            <th>Email</th>
            <td>{{ $account->user->email }}</td>
          </tr>
          <tr>
            <td><h5>Informações</h5></td>
          </tr>
          <tr>
            <th>Nome da conta</th>
          <td>{{ $account->name }}</td>
          </tr>
          <tr>
            <th>Tipo</th>
            <td>{{ $account->type }}</td>
          </tr>
          <tr>
            <th>Grupo</th>
            <td>{{ $account->group->name }}</td>
          </tr>
          <tr>
          <th>Ativo</th>
          <td>{{ ($account->ativo == 0) ? "não" : "sim" }}</td>
          </tr>
          <tr>
            <th>Observações</th>
            <td>{{ $account->obs }}</td>
          </tr>
        </table>
        @if ($account->ativo)
            <form action="{{ route('password.renew', $account) }}" method="post">
              @csrf
              <button type="submit" class="btn btn-primary">Recuperar senha</button>
            </form>
        @endif
      </div>
  </div>
@endsection
