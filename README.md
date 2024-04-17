
# Teste Febrapar

Objetivo: O desafio é desenvolver um sistema de gerenciamento de tarefas.

### Passo a passo
Clone Repositório
```sh
git clone https://github.com/rafaelcarval/febrafar_test
```
```sh
cd febrafar_test/
```
Crie o Arquivo .env
```sh
cp .env.example .env
```


Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME="febrafar_test"
APP_URL=http://localhost:8989

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```


Suba os containers do projeto
```sh
docker-compose up -d
```


Acessar o container
```sh
docker-compose exec app bash
```


Instalar as dependências do projeto
```sh
composer install
```

Gerar a key do projeto Laravel
```sh
php artisan key:generate
```

Gerar as migrations
```sh
php artisan migrate
```

Acessar o projeto - api Swagguer via auto scan
[http://localhost:8989/api-docs-ui#/](http://localhost:8989/api/documentation)

ou

Criar a documentação swagger
```sh
php artisan l5-swagger:generate
```

Acessar o projeto - api Swagguer
[http://localhost:8989/api/documentation](http://localhost:8989/api/documentation)

Testes unitários
```sh
php artisan test
```


Além dos testes unitários, disponibilizei via e-mail a collection do postman com testes completos
