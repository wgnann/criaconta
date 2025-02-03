@extends ('laravel-usp-theme::master')

@section ('content')
  <h4>Solicitação enviada</h4>
  <p>Pedido de recuperação de senha realizado para a conta: {{ $password_request->account->username }}.</p>
  <p>Por favor, dentro de alguns minutos, verifique o email: {{ $password_request->account->user->email }}.</p>
@endsection
