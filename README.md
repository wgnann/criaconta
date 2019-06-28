## Dependências
 * [uspdev/laravel-usp-theme](https://github.com/uspdev/laravel-usp-theme)
 * [uspdev/senhaunica-socialite](https://github.com/uspdev/senhaunica-socialite)
 * [idmail](https://github.com/wgnann/idmail)

## Como configurar o acesso via API
 * Colocar no `.env` a variável `DEPLOY_KEY`;
 * Acessar os endereços da API usando a variável GET `api_key` com o mesmo valor de `DEPLOY_KEY`.

### Chamadas disponíveis
 * `accounts/todo`: mostra lista de contas não processadas;
 * `accounts/{id}/activate`: ativa uma dada conta;
 * `accounts/{id}/cancel`: cancela um pedido de conta.

## Como instalar o IDMail
Para instalar IDMail no laravel, basta:
 * copiá-lo para o diretório app/Tools ou outro preferido;
 * adicioná-lo no namespace.

É necessário um arquivo `.env` contendo as variáveis:
 * `LOGIN`
 * `PASSWORD`

## Grupos
Não dispomos de uma interface web para adicionar grupos. Por ora, o procedimento é criar os grupos como no [exemplo](tools/groups.php).

Para usá-lo, rodar o `php artisan tinker` e:
```php
require('tools/group.php')
Group::all()
```
