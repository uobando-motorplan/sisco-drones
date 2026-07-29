<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Levantar la aplicación

### Preparación del entorno

1. Clonar el repositorio:

```
git clone <url-del-repositorio>
cd <nombre-del-proyecto>
```

2. Instalar dependencias:

```
composer install
```

3. Copiar el archivo de entorno y configurarlo:

```
cp .env.example .env
```

### Base de datos

5. Ejecutar las migraciones:

```
php artisan migrate
```

### Ejecución

6. Levantar el servidor de desarrollo:

```
php artisan serve
```

## Stack utilizado

- PHP 8.2.x
- Laravel 8.x
- MySQL
