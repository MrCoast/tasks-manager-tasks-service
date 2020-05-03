# PHP Tasks Manager - TasksService

### Run ```composer``` commands
```bash
docker run --rm -it -v $PWD:/app -u $(id -u):$(id -g) composer <command>
```

### Run Symfony's ```bin/console``` commands
```bash
docker-compose run -u $(id -u):$(id -g) php bin/console <command>
```

#### Run all Doctrine migrations
```bash
docker-compose run -u $(id -u):$(id -g) php bin/console doctrine:migrations:migrate -n
```

#### Populate the database with fixtures
```bash
docker-compose run -u $(id -u):$(id -g) php bin/console doctrine:fixtures:load -n
```

### Connect to the local database
```bash
docker run -it --network doclertest_default --rm mysql:8 mysql -h'database' -u'tasks' -p'tasks' tasks
```

### Run code sniffer
```bash
# phpcs
docker-compose run -u $(id -u):$(id -g) php vendor/bin/phpcs src
# phpcbf
docker-compose run -u $(id -u):$(id -g) php vendor/bin/phpcbf src
```

### Swagger UI

In web browser, go to http://localhost:8080/api/doc.

### Testing
```bash
# run PHPUnit
docker-compose run -u $(id -u):$(id -g) php bin/phpunit
```
