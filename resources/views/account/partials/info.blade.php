<div class="card">
    <div class="card-header">
        <h5 class="card-title">{{ $account->username }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <p class="font-weight-bold">Nome da conta</p>
            </div>
            <div class="col">
                <p>{{ $account->name }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="font-weight-bold">Tipo</p>
            </div>
            <div class="col">
                <p>{{ $account->type }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="font-weight-bold">Grupo</p>
            </div>
            <div class="col">
                <p>{{ $account->group->name }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="font-weight-bold">Status</p>
            </div>
            <div class="col">
                <p>{{ $account->ativo ? "ativo" : "inativo" }}</p>
            </div>
        </div>
        @if ($account->obs)
            <div class="row">
                <div class="col">
                    <p class="font-weight-bold">Observações</p>
                </div>
                <div class="col">
                    <p>{{ $account->obs }}</p>
                </div>
            </div>
        @endif
        @if ($account->ativo)
            <div class="row">
                <div class="col">
                </div>
                <div class="col">
                    <form action="{{ route('password.renew', $account) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary">Recuperar senha</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
