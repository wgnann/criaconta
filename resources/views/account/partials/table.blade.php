<table class="table table-striped datatable-simples dt-paging-10">
    <thead>
        <tr class="table-info">
            <th>Login</th>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($accounts as $account)
            <tr>
                <td><a href="{{ route('account.show', $account)}}">{{ $account->username }}</a></td>
                <td>{{ $account->name }}</td>
                <td>{{ $account->type }}</td>
                <td>
                    @if ($account->ativo)
                        <p class="text-success">ativo</p>
                    @else
                        <p class="text-danger">criando</p>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
