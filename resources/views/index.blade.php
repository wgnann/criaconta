@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('content')

    @auth
        <div class="panel">
            <table class="table table-sm">
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
                <tr class="table-warning">
                    <th>status</th>
                    <td>(virá no context)</td>
                </tr>
            </tbody>
            </table>
            <button type="button" class="btn btn-primary">Criar</button>
        </div>
    @else
        <div class="panel">
            Faça o <a href="/login/senhaunica">login</a>!
        </div>
    @endauth
@endsection

@section ('footer')
@endsection

