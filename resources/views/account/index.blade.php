@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('content')
  <div class="panel">
    @if ($account)
      <h4>Informações da conta pessoal</h4>
      <table class="table table-sm">
      <tbody>
        <tr>
          <th>Responsável</th>
          <td>{{ $account->user->nusp }}</td>
        </tr>
        <tr>
          <th>username</th>
          <td>{{ $account->username }}</td>
        </tr>
        <tr>
          <th>Nome</th>
          <td>{{ $account->name }}</td>
        </tr>
        <tr>
          <th>Grupo</th>
          <td>{{ $account->group->name }}</td>
        </tr>
        @if ($account->ativo)
          <tr class="table-success">
            <th>status</th>
            <td>ativa</td>
          </tr>
        @else
          <tr class="table-warning">
            <th>status</th>
            <td>não ativa</td>
          </tr>
        @endif
      </tbody>
      </table>
    @else
      <h4>Conta não criada</h4>
      <form action="{{ route('accounts') }}" method="post">
        @csrf
        <table class="table table-sm table-info">
        <tbody>
          <tr>
            <th>NUSP</th>
            <td>{{ Auth::user()->nusp }}</td>
          </tr>
          <tr>
            <th>Nome</th>
            <td>{{ Auth::user()->name }}</td>
          </tr>
          <tr>
            <th>email</th>
            <td>{{ Auth::user()->email }}</td>
          </tr>
          <tr>
            <th>Grupo</th>
            <td>
              @foreach ($groups as $group)
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="group" value="{{ $group->id }}">
                  <label class="form-check-label">{{ $group->name }}</label>
                </div>
              @endforeach
            </td>
          </tr>
        </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Criar</button>
      </form>
    @endif
  </div>
@endsection

@section ('footer')
@endsection

