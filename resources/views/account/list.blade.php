@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('content')
  @include('messages.errors')
  <div class="panel">
    <h4>Contas</h4>
    <form action="{{ route('account.list') }}" method="get">
      <input type="text" name="search" placeholder="Login ou Número USP" />
      <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    <div class="border border-success my-1 p-1">
      <table class="table table-sm table-borderless">
        <tr>
          <th>Login</th>
          <th>Nome</th>
          <th></th>
        </tr>
        @foreach ($accounts as $account)
          <tr>
            <td>{{ $account->username }}</td>
            <td>{{ $account->name }}</td>
            <td><a href="{{ route('account.show', $account)}}">Ver detalhes</a></td>
          </tr>
        @endforeach
      </table>
    </div>
  </div>
@endsection

@section ('footer')
@endsection

