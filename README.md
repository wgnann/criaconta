## Dependências fora do composer
  * [idmail](https://github.com/wgnann/idmail)

## Como configurar o acesso via API
  * Colocar no `.env` a variável `API_KEY`;
  * Acessar os endereços da API usando a variável GET `api_key` com o mesmo valor de `API_KEY`.

### Chamadas disponíveis
  * `accounts/todo`: mostra lista de contas não processadas;
  * `accounts/{id}/activate`: ativa uma dada conta;
  * `accounts/{id}/cancel`: cancela um pedido de conta.

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
Há dois utilitários de terminal(tools/criaconta.py) que conversa com a API. O objetivo é utilizá-lo para integrar com o lado de dentro da rede. São estes:
  * [versão CLI para a API](tools/criaconta.py)
  * [backend de criação de contas](tools/contas.py)

A configuração da API depende dos parâmetros:
  * `API_KEY`: a mesma requerida pela API;
  * `BASE_URL`: URL onde roda a API.

Já o backend depende da API e dos parâmetros:
  * `SKEL_DIR`: localização do modelo de `skel` do usuário;
  * `DISK_QUOTA`: quota de disco do usuário, em bytes;
  * `KRB_ADD_PRINCIPAL`: principal do Kerberos utilizado para adicionar contas;
  * `KRB_ADD_KEYTAB`: localização da keytab associada ao principal correspondente;
  * `KRB_CPW_PRINCIPAL`: principal do Kerberos utilizado para renovar senhas;
  * `KRB_CPW_KEYTAB`: localização da keytab associada ao principal correspondente;
  * `KRB_DEL_PRINCIPAL`: principal do Kerberos utilizado para remover contas;
  * `KRB_DEL_KEYTAB`: localização da keytab associada ao principal correspondente;
  * `PRINT_QUOTA`: quota de impressão geral, em páginas;
  * `PROF_PRINT_QUOTA`: quota de impressão para docentes, em páginas;
  * `SMTP_SERVER`: endereço do servidor de SMTP utilizado;
  * `SMTP_USER`: usuário de SMTP AUTH;
  * `SMTP_PASS`: senha de SMTP AUTH;
  * `MAIL_SENDER`: endereço de email do remetente;
  * `BACKUP_DIR`: diretório de backup no servidor de backup.
