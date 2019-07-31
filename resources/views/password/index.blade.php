@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('content')
  @if ($password_request)
    <h4>Solicitação enviada</h4>
    <p>Por favor, dentro de alguns minutos, verifique o email: {{ $password_request->account->user->email }}.</p>
  @else
    <h4>Recuperar senha</h4>
    <p>A senha nova será enviada para o email principal.</p>
    <form action="{{ route('password.renew') }}" method="post">
      @csrf
      <button type="submit" class="btn btn-primary">Recuperar</button>
    </form>
  @endif
@endsection

@section ('footer')
@endsection

