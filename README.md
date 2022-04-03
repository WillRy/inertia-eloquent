# Aplicação demonstrativa para inertia/vue (EM ANDAMENTO)


## Como executar?

**Com docker**
```shell
# sobe os containers
docker-compose up -d

# copie as configurações de banco do .env.example
# para o .env

#entra no container do php
docker exec -it phpfpm bash

# sobre as migrations(cria o banco
php artisan migrate:fresh --seed


#sai do container do php
exit

# entra no container do node
docker exec -it node bash

# realiza o build
npm run watch
```

**Sem docker**
```shell
# sobe a aplicação
php artisan serve


# sobre as migrations(cria o banco
php artisan migrate:fresh --seed

# realiza o build
npm run watch
```

## URL
URL: http://localhost:8000/login

## Credenciais

email: teste@teste.com
senha: 123456
