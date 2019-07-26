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
  @auth
    <h4>Informações das contas institucionais</h4>
    <div class="panel">
      @if ($todo)
        <div class="border border-info my-1 p-1">
          <h5 class="text-info">Contas não criadas</h5>
          <form action="{{ route('institutional.accounts') }}" method="post">
            @csrf
            <table class="table table-sm">
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
                    <td>{{ $todo_account['email'] }}</td>
                    <td>{{ $todo_account['name'] }}</td>
                    <td><input type="radio" name="email" value="{{ $todo_account['email'] }}" /></td>
                  </tr>
                @endforeach
                <tr>
                  <td></td>
                  <td>
                    <div class="input-group">
                      <textarea class="form-control" placeholder="Observações" name="obs"></textarea>
                      <button class="btn btn-info" type="submit">Criar</button>
                    </div>
                  </td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      @endif
      @if ($queued)
        <div class="border border-danger my-1 p-1">
          <h5 class="text-danger">Contas não ativadas</h5>
          <table class="table table-sm">
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
                <td></td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      @endif
      @if ($active)
        <div class="border border-success my-1 p-1">
          <h5 class="text-success">Contas ativas</h5>
          <table class="table table-sm">
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
                <td></td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      @endif
      @if (!$todo and !$queued and !$active)
        <h5>Sem conta institucional.</h5>
      @endif
    </div>
  @endauth
@endsection

@section ('footer')
@endsection

