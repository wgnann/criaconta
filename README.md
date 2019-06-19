##O que é
IDMail é um hack que permite trabalhar com a interface do id-admin da USP sem precisar de um navegador. Ele dispõe por ora de um módulo de login e de uma interface de consulta de email, pode ser estendido para trabalhar com os jsons oriundos de lá sem muito trabalho.

##Dependências
 * [uspdev/laravel-usp-theme](https://github.com/uspdev/laravel-usp-theme)
 * [uspdev/senhaunica-socialite](https://github.com/uspdev/senhaunica-socialite)
 * [idmail](https://github.com/wgnann/idmail)

##Como instalar o IDMail
Para instalar IDMail no laravel, basta:
 * copiá-lo para o diretório app/Tools ou outro preferido;
 * adicioná-lo no namespace.

É necessário um arquivo `.env` contendo as variáveis:
 * `LOGIN`
 * `PASSWORD`
