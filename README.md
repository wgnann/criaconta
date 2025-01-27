## Como configurar o acesso via API
  * Colocar no `.env` a variável `API_KEY`;
  * Acessar os endereços da API usando a variável GET `api_key` com o mesmo valor de `API_KEY`.

### Chamadas disponíveis
  * `accounts/todo`: lista contas não processadas;
  * `accounts/{id}/activate`: ativa uma dada conta;
  * `accounts/{id}/cancel`: cancela um pedido de conta;
  * `accounts/{id}/delete`: apaga uma conta;
  * `accounts/{username}/info`: JSON com informações de uma conta;
  * `password/requests`: lista pedidos de renovação de senha;
  * `password/{id}/reset`: processa pedido de renovação de senha.

Sugere-se usar o cliente criaconta.py.

## Deployment manual
```console
# tudo aqui assume estar no diretório recém clonado

cp .env.example .env
# consertar o que for relevante:
#  - APP_URL
#  - DB
#  - API_KEY
#  - SENHAUNICA
#  - IDMAIL

composer install

# gerar chave
php artisan key:generate

# banco de dados
touch database/database.sqlite
php artisan migrate:fresh --seed
```

## Docker
```console
# build
docker build -t criaconta .

# env
#   - preencher o env como no exemplo;
#   - tomar cuidado com aspas (duplas ou simples).

# banco de dados
#   - criar local com touch;
#   - usar bind mount;
#   - tomar cuidado com permissões:
#     - em especial a do diretório onde residirá o database.sqlite.
#   - rodar o migrate:fresh via docker exec.
docker exec -it criaconta php artisan migrate:fresh --seed

# cache do idmail
#   - criar diretório local;
#   - usar bind mount;
#   - tomar cuidado com permissões.

# deployment simples
docker run -d --rm --name criaconta --env-file=/path/to/.env -v /path/to/database.sqlite:/var/www/html/database/database.sqlite -v /path/to/cache:/var/www/html/resources/cache criaconta
```

## Utilitário de terminal
Há um conjunto de script Python que residem no diretório `tools`. O contas.py é o backend de criação de contas.

A configuração da API depende dos parâmetros que estão no .env.example.
