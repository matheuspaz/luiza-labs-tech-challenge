# Digital Maps by Luiza Labs

Digital Maps é uma solução para trackear pontos de interesse.

# Instalando o projeto

Considerando que você tenha o **docker** e o **docker compose** instalado em sua maquina, faça o clone do projeto.
Após fazer o clone do projeto, rode o seguinte comando na pasta raiz:
_obs_: _se você tiver uma versão mais antiga do docker compose, talvez seja necessário utilizar o comando com hífen ao invés de espaço._
**\*Exemplo**: docker-compose .....\*

```bash
docker compose up -d
```

Após finalizar o build e a criação dos containers, execute o seguinte comando para permitir a execução do post script:

```bash
docker exec digital-maps-php chmod+x init.sh
```

Executado o comando de permissão, execute o post script com o seguinte comando:

```bash
docker exec digital-maps-php ./init.sh
```

Esse script executará os seguintes comandos:

-   **composer install** (para instalar as dependências)
-   **migrate** (para executar as migrações e criar as tabelas)
-   **swagger docs** (gera documentação do swagger)

Após a execução do post script, execute o seguinte comando para adicionar dados com seeders:

```bash
docker exec digital-maps-php php artisan db:seed
```

Pronto, o sistema está rodando com todas os scripts necessários já executados.
O sistema executara na seguinte URL: **http://localhost:8001**

# Documentação da API (Swagger)

Para acessar a documentação da API, você deve acessar a seguinte URL: **http://localhost:8001/api/documentation**

O usuario criado pelo seed para os testes, contem os seguintes dados de autenticação:

-   email: **admin@digitalmaps.com**
-   password: **digital-maps**

# Sobre a solução (proposta)

A solução foi utilização de uma API em formato MVC de arquitetura
Utilizando os recursos do laravel para roteamento e o eloquent como ORM.
Foi feita separação de conceitos utilizando serviços e repositórios.
Utilizado enums e validações de parâmetros no controller.

## Testes

Foram criados testes unitários para os serviços e testes de feature.

Para executar os testes, execute o seguinte comando:

```sh
docker exec digital-maps-php php artisan test
```
