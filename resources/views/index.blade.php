@extends ('laravel-usp-theme::master')

@section ('title') Cria Conta - IME-USP @endsection

@section ('content')
  @auth
    <div class="panel">
      <h4>Sistema de criação de contas</h4>
      <p>Utilize o menu acima.</p>
    </div>
  @else
    <div class="panel">
      <p>
      Utilize o menu acima. Faça o <a href="/login/senhaunica">login</a>!
      </p>
    </div>
  @endauth
@endsection

@section ('footer')
@endsection

