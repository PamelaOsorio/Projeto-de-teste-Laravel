# Projeto de Teste - Laravel
Projeto simples de teste desenvolvido com Laravel para avaliação técnica.

## Requisitos:
- PHP > 8.0 (versão utilizada 8.4.8)
- Composer (versão utilizada 2.8.9)
- Laravel (versão utilizada 12.18.0)
- laravel/sanctum ^4.1 (para autenticação via API)
- laravel/tinker ^2.10 (ferramenta para linha de comando)
- Banco de dados SQLite (ou MySQL/PostgreSQL conforme configuração)

## Opcional
- Laravel Sail (versão utilizada 1.43)

## Comando para instalação de dependencias
composer install
## Comando para rodar as migrations
- php artisan migrate
- php artisan migrate:fresh --seed --env=example (para limpar e gerar novamente)
- php vendor/laravel/sail/bin/sail artisan migrate (gerar migrations direto do Laravel Sail)
## Comando para teste 
php artisan test

## Suite de cases
✅ Validar cadastro de clientes com dados válidos 
✅ Verificar que apenas usuários autenticados podem cadastrar clientes. (autenticação com sanctum)
✅ Validar CNPJ único (Evita duplicação)
✅ Validar que apenas Admin pode aprovar e reprovar cadastro
✅ Verificar listagem paginada de clientes
✅ Validar filtros por status e nome


## Resultado do teste rodando
![image](https://github.com/user-attachments/assets/9a79e17d-9c84-4e04-83bd-08e2e0298a1a)



