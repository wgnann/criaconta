@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('content')
  @include('messages.errors')
  <div class="panel">
    <h4>Conta: {{ $account->username }}</h4>
    <div class="border border-success my-1 p-1">
      <table class="table table-sm table-borderless">
        <tr>
          <td><h5 class="text-success">Responsável</h5></td>
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
          <td><h5 class="text-success">Informações</h5></td>
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
    </div>
  </div>
@endsection

@section ('footer')
@endsection

