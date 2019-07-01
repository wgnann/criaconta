## Dependências
  * [uspdev/laravel-usp-theme](https://github.com/uspdev/laravel-usp-theme)
  * [uspdev/senhaunica-socialite](https://github.com/uspdev/senhaunica-socialite)
  * [idmail](https://github.com/wgnann/idmail)

## Como configurar o acesso via API
  * Colocar no `.env` a variável `API_KEY`;
  * Acessar os endereços da API usando a variável GET `api_key` com o mesmo valor de `API_KEY`.

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

## Utilitário de terminal
Há dois utilitários de terminal(tools/criaconta.py) que conversa com a API. O objetivo é utilizá-lo para integrar com o lado de dentro da rede. São estes:
  * [versão CLI para a API](tools/criaconta.py)
  * [backend de criação de contas](tools/contas.py)

A configuração da API depende dos parâmetros:
  * `API_KEY`: a mesma requerida pela API;
  * `BASE_URL`: URL onde roda a API.

Já o backend depende da API e dos parâmetros:
  * `SKEL_DIR`: localização do modelo de `skel` do usuário.
