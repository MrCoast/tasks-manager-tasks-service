# PHP Tasks Manager - TasksService

### Run ```composer``` commands
```bash
docker run --rm -it -v $PWD:/app -u $(id -u):$(id -g) composer <command>
```

### Run Symfony's ```bin/console``` commands
```bash
docker-compose run -u $(id -u):$(id -g) php bin/console
```
