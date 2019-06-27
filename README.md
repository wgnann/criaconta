## Dependências
 * [uspdev/laravel-usp-theme](https://github.com/uspdev/laravel-usp-theme)
 * [uspdev/senhaunica-socialite](https://github.com/uspdev/senhaunica-socialite)
 * [idmail](https://github.com/wgnann/idmail)

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
