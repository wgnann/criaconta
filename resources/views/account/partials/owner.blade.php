<div class="card">
    <div class="card-header">
        <h5 class="card-title">Responsável</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col md-3">
                <p class="font-weight-bold">Número USP</p>
            </div>
            <div class="col md-3">
                <p>{{ $account->user->codpes }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col md-3">
                <p class="font-weight-bold">Nome</p>
            </div>
            <div class="col md-3">
                <p>{{ $account->user->name }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col md-3">
                <p class="font-weight-bold">Email</p>
            </div>
            <div class="col md-3">
                <p>{{ $account->user->email }}</p>
            </div>
        </div>
    </div>
</div>
