##Installation
`cd docker && docker-compose up -d`

`cd docker && docker-compose exec php-dev composer install`

`cd docker && docker-compose exec php-dev php artisan migrate`

`cd docker && docker-compose exec php-dev php artisan db:seed`

### Graphql URL http://server_url/api
### Graphql IDE http://server_url/graphql-playground
