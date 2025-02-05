@extends ('laravel-usp-theme::master')

@section ('content')
  <h4>Informações das contas institucionais</h4>
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
