<div class="card">
    <div class="card-header">
        <h5 class="card-title">Dados da conta local</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('local.account.store') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col">
                    <label for="name">Nome</label>
                    <input class="form-control" type="text" id="name" name="name" placeholder="nome completo" />
                </div>
                <div class="form-group col">
                    <label for="username">Login</label>
                    <div class="input-group">
                        <input class="form-control" type="text" id="username" name="username" placeholder="nome de usuário" pattern="[A-Za-z0-9]*" title="Apenas letras ou números." />
                        <div class="input-group-append">
                            <span class="input-group-text">-local</span>
                        </div>
                    </div>
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
            <div class="form-group input-group">
                <textarea class="form-control" name="obs" placeholder="observações"></textarea>
                <button type="submit" class="btn btn-info">Criar</button>
            </div>
        </form>
    </div>
</div>
