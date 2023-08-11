# Setup This Project On Docker

Admin Account Dashboard (/admin):
admin@gmail.com
123456

Access the project http://localhost:8989
Access phpmyadmin http://localhost:8080 

phpmyadmin /mysql account:
    root
    root


```sh
cp .env.example .env
```

Upload the project containers

```sh
sudo docker-compose up -d
```

Access the container

```sh
sudo docker-compose exec app bash
```

Install project dependencies

```sh
composer install
```

Generate Laravel project key

```sh
php artisan key:generate
```

Migrate

```sh
php artisan migrate
```

Seed Data

```sh
php artisan db:seed --class=SetupSeeder
```



