<div class="card">
    <div class="card-header">
        <h5 class="card-title">Conta institucional a ser criada</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('institutional.account.store') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col">
                    <label for="email">Email</label>
                    <select class="custom-select" id="email" name="email">
                        @foreach ($todo as $todo_key => $todo_account)
                            <option value="{{ $todo_account['email'] }}">
                            {{ $todo_account['email'] }} - {{ $todo_account['name'] }}
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
