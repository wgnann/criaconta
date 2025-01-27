@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('content')
  @include('messages.errors')
  <div class="panel">
    <h4>Conta pessoal</h4>
    @if ($account)
      @if ($account->ativo)
        <div class="border border-success my-1 p-1">
          <h5 class="text-success">Conta ativa</h5>
      @else
        <div class="border border-danger my-1 p-1">
          <h5 class="text-danger">Conta em criação (demora até 5min)</h5>
      @endif
          <table class="table table-sm table-borderless">
            <tr>
              <th>Responsável</th>
              <td>{{ $account->user->codpes }}</td>
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
          </table>
        </div>
    @else
      <form action="{{ route('accounts') }}" method="post">
        @csrf
        <div class="border border-info my-1 p-1">
          <h5 class="text-info">Criar conta pessoal</h5>
          <table class="table table-sm table-borderless">
            <tr>
              <th>Número USP</th>
              <td>{{ Auth::user()->codpes }}</td>
            </tr>
            <tr>
              <th>Nome</th>
              <td>{{ Auth::user()->name }}</td>
            </tr>
            @if ($idmail)
              <tr>
                <th>Email</th>
                <td>{{ Auth::user()->email }}</td>
              </tr>
            @else
              <tr>
                <th>username</th>
                <td>
                  <input class="form-control" type="text" id="username" name="username" placeholder="nome de usuário" pattern="[A-Za-z0-9]*" title="Apenas letras ou números"/>
                </td>
              </tr>
            @endif
            <tr>
              <th>Grupo</th>
              <td>
                @foreach ($groups as $group)
                  <div class="form-check">
                    <input class="form-check-input" type="radio" id="group-{{ $group->id }}" name="group" value="{{ $group->id }}">
                    <label class="form-check-label" for="group-{{ $group->id }}">{{ $group->name }}</label>
                  </div>
                @endforeach
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <button type="submit" class="btn btn-info">Criar</button>
              </td>
            </tr>
          </table>
        </div>
      </form>
    @endif
  </div>
@endsection

@section ('footer')
@endsection

