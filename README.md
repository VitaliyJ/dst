### Инструкция по запуску:

- создать и настроить `.env` файл (по примеру `.env.example`)
- создать и настроить `server.conf` файл (по примеру `server_example.conf`)
- запустить комманду `docker-compose up -d`
- запустить комманду `docker-compose exec dst-test composer install`
- запустить комманду `docker-compose exec dst-test php migrate.php`

тестировал API через postman


### Сколько времени потрачено:
>настроить docker: час-полтора

>набросать примерную архитектуру сервиса: полтора часа

>реализация бизнес-логики: 2 часа

>тестирование API и правки: час