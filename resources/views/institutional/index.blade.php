@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('styles')
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('/vendor/laravel-usp-theme/css/style.css')}}">
  <style>
    .table{table-layout:fixed;}
  </style>
@endsection

@section ('content')
  <h4>Informações das contas institucionais</h4>
  @if ($active)
    <div class="border border-success my-1 p-1">
      <h5 class="text-success">Contas ativas</h5>
      <table class="table table-sm table-borderless">
        <thead>
          <tr>
            <th>Email</th>
            <th>Nome</th>
            <th></th>
          </td>
        </thead>
        <tbody>
        @foreach ($active as $active_account)
          <tr>
            <td>{{ $active_account->username }}@ime.usp.br</td>
            <td>{{ $active_account->name }}</td>
            <td><a href="{{ route('account.show', $active_account) }}">Ver detalhes</a></td>
            <td>
              <form action="{{ route('password.renew', $active_account) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-primary">Recuperar senha</button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  @endif
  @if ($queued)
    <div class="border border-danger my-1 p-1">
      <h5 class="text-danger">Contas em criação (demora até 5min)</h5>
      <table class="table table-sm table-borderless">
        <thead>
          <tr>
            <th>Email</th>
            <th>Nome</th>
            <th></th>
          </td>
        </thead>
        <tbody>
        @foreach ($queued as $queued_account)
          <tr>
            <td>{{ $queued_account->username }}@ime.usp.br</td>
            <td>{{ $queued_account->name }}</td>
            <td><a href="{{ route('account.show', $queued_account) }}">Ver detalhes</a></td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  @endif
  @if ($todo)
    <div class="border border-info my-1 p-1">
      <h5 class="text-info">Contas não criadas</h5>
      <form action="{{ route('institutional.account.store') }}" method="post">
        @csrf
        <table class="table table-sm table-borderless">
          <thead>
            <tr>
              <th>Email</th>
              <th>Nome</th>
              <th>Escolhida</th>
            </td>
          </thead>
          <tbody>
            @foreach ($todo as $todo_key => $todo_account)
              <tr>
                <td><label for="{{ $todo_account['email'] }}">{{ $todo_account['email'] }}</label></td>
                <td><label for="{{ $todo_account['email'] }}">{{ $todo_account['name'] }}</label></td>
                <td><input type="radio" id="{{ $todo_account['email'] }}" name="email" value="{{ $todo_account['email'] }}" /></td>
              </tr>
            @endforeach
            <tr>
              <td colspan="3">
                <div class="input-group">
                  <textarea class="form-control" placeholder="Observações" name="obs"></textarea>
                  <button class="btn btn-info" type="submit">Criar</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  @endif
  @if (!$todo and !$queued and !$active)
    <h5>Sem conta institucional.</h5>
  @endif
@endsection

@section ('footer')
@endsection

