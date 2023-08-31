## Dependências fora do composer
  * [idmail](https://github.com/wgnann/idmail)

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

## Deployment
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

# idmail
mkdir app/Tools
curl https://raw.githubusercontent.com/wgnann/idmail/master/IDMail.php | sed 's/<?php/<?php\n\nnamespace App\\Tools;/g' > app/Tools/IDMail.php
```

## Utilitário de terminal
Há um conjunto de script Python que residem no diretório `tools`. O contas.py é o backend de criação de contas.

A configuração da API depende dos parâmetros que estão no .env.example.
