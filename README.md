# Setup This Project On Docker

Admin Account Dashboard (/admin):
admin@gmail.com
123456

Access the project http://localhost:8989
Access phpmyadmin http://localhost:8080 

phpmyadmin /mysql account:
    root
    roots


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

Access the container on the second time

```sh
docker ps
docker exec -it <containerID> bash
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

Import Database
Access to mysql container using the (second time access command above)
input the following

```sh
docker cp docsach.sql <containerId>:docsach.sql
docker exec -it <containerID> bash
mysql -u root -p laravel < docsach.sql
Enter password: root
```


