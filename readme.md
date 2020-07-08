##test task 2
```sh
git clone https://github.com/warwar/string2words.git
cd string2words
docker-compose up -d
docker-compose run --rm t2-php-cli composer install
docker-compose run --rm t2-php-cli vendor/bin/phpunit
```
