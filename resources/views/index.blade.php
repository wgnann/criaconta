@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('content')
  @auth
    <h1>Sistema de criação de contas</h1>
  @else
    <div class="panel">
      Faça o <a href="/login/senhaunica">login</a>!
    </div>
  @endauth
@endsection

@section ('footer')
@endsection

