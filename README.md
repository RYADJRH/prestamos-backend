## Backend - Sistema de prestamos

Sistema de prestamos para pequeñas empresas y prestadores individuales.

### Instalación

NOTA:El sistema usa Laravel sail, por lo que debe tener instalado docker.

1.- copiar variables de entorno

```
cp .env.example .env
```

2.- Instalar sail para proyecto ya existente.

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer update laravel/sail
```

3.-Levantar servicios

```
./vendor/bin/sail up -d
```

4.- Correr migraciones para base de datos

```
./vendor/bin/sail artisan migrate
```

5.- Correr seed

```
./vendor/bin/sail artisan db:seed
```
