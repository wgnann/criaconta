@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('content')
  @auth
    <h4>Informações das contas institucionais</h4>
    <div class="panel">
      <table class="table table-sm">
        <thead>
          <tr>
            <th>Email</th>
            <th>Nome</th>
          </td>
        </thead>
        <tbody>
        @foreach ($emails as $email)
          <tr>
            <td>{{ $email['email'] }}</td>
            <td>{{ $email['name'] }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  @endauth
@endsection

@section ('footer')
@endsection

