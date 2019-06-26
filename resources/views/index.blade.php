@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('content')

    @auth
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
                    @if ($account->status)
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
                </tbody>
                </table>
                <button type="button" class="btn btn-primary">Criar</button>
            @endif
        </div>
    @else
        <div class="panel">
            Faça o <a href="/login/senhaunica">login</a>!
        </div>
    @endauth
@endsection

@section ('footer')
@endsection

