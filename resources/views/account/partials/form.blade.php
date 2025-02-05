<div class="card">
    <div class="card-header">
        <h5 class="card-title">
            Número USP: {{ Auth::user()->codpes }}
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('account.store') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col">
                    <label for="name">Nome</label>
                    <input class="form-control" type="text" id="name" value="{{ Auth::user()->name }}" readonly />
                </div>
                <div class="form-group col">
                    <label for="username">Login</label>
                    @if ($idmail)
                        <input class="form-control" type="text" id="username" value="{{ $username }}" readonly />
                    @else
                        <input class="form-control" type="text" id="username" name="username" placeholder="nome de usuário" pattern="[A-Za-z0-9]*" title="Apenas letras ou números." />
                    @endif
                </div>
                <div class="form-group col">
                    <label for="group">Grupo</label>
                    <select class="custom-select" id="group" name="group">
                        <option select>Selecionar grupo</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">
                            {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <button type="submit" class="btn btn-info">Criar</button>
                </div>
            </div>
        </form>
    </div>
</div>
