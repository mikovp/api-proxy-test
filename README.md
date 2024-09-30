# api-proxy-test

Platform:

* [Laravel 11](https://laravel.com/docs/8.x)

Dependencies:
* [lorisleiva/laravel-actions](https://github.com/lorisleiva/laravel-actions)
* [darkaonline/l5-swagger](https://github.com/DarkaOnLine/L5-Swagger)


### Developer Build

* Start Docker

    ```bash
    docker-compose -f docker/dev/docker-compose.yml up -d --build
    ```

* Debugging && Logs

    ```bash
    docker-compose -f docker/dev/docker-compose.yml logs -f
    docker-compose -f docker/dev/docker-compose.yml exec app sh
    ```

### API Endpoints
* [Swagger](http://localhost:3000/api/documentation)
