# Dashboard Tech

Este proyecto es una aplicación PHP que utiliza Doctrine ORM y está configurada con Docker para facilitar el desarrollo y despliegue.

## Requisitos Previos

- Docker
- Docker Compose
- PHP 8.x
- Composer

## Tecnologías Utilizadas

- PHP
- Doctrine ORM
- PHPUnit para pruebas
- Docker
- MySQL

## Configuración del Proyecto

### 1. Clonar el Repositorio

```bash
git clone [url-del-repositorio]
cd dashboard-tech
```

### 2. Configuración del Entorno Docker

El proyecto incluye una configuración Docker lista para usar. Para iniciar los contenedores:

```bash
docker-compose up -d
```

### 3. Instalación de Dependencias

```bash
composer install
```

### 4. Base de Datos

La base de datos se inicializa automáticamente con Docker Compose usando el archivo `init-dbs.sql`.

## Estructura del Proyecto

```
dashboard-tech/
├── src/           # Código fuente de la aplicación
├── tests/         # Pruebas unitarias y de integración
├── vendor/        # Dependencias de Composer
├── Dockerfile     # Configuración de Docker
├── docker-compose.yml
├── init-dbs.sql   # Script de inicialización de la base de datos
└── composer.json  # Gestión de dependencias
```

## Desarrollo

### Ejecutar Pruebas

```bash
composer test          # Ejecutar todas las pruebas
composer test:unit    # Ejecutar solo pruebas unitarias
```

### Configuración de Xdebug

El proyecto incluye configuración de Xdebug para desarrollo. La configuración se encuentra en `xdebug.ini`.

## Dependencias Principales

- doctrine/orm: ^3
- doctrine/dbal: ^4
- symfony/cache: ^7
- phpunit/phpunit: ^12.0 (desarrollo)

## Comandos del Makefile

El proyecto incluye un Makefile que facilita diversas tareas comunes. A continuación se muestra una lista de los comandos disponibles:

- up: Levanta los contenedores usando Docker Compose, instala las dependencias con Composer y ejecuta las migraciones de la base de datos.
- down: Detiene y elimina los contenedores y volúmenes creados.
- build: Reconstruye las imágenes Docker sin utilizar la caché.
- test: Ejecuta todas las pruebas (después de ejecutar migraciones de test).
- test-unit: Ejecuta solo las pruebas unitarias ubicadas en tests/Unit.
- migrate: Ejecuta las migraciones de la base de datos para el entorno de desarrollo.
- migrate-test: Ejecuta las migraciones de la base de datos para el entorno de testing.
- db-reset: Reinicia la base de datos (desarrollo y test), eliminando y recreando las bases de datos, y ejecuta las migraciones.
- install: Instala las dependencias de Composer, crea el archivo .env y ejecuta las migraciones.
- dump: Actualiza el autoload de Composer con optimizaciones.
- console: Accede a la consola de comandos dentro del contenedor de la aplicación.
- db-connect: Se conecta a la base de datos utilizando el cliente MySQL en el contenedor de la base de datos.
- logs: Muestra los logs del contenedor de la aplicación.
- logs-db: Muestra los logs del contenedor de la base de datos.

> [!NOTE]
> Para ejecutar los comandos del Makefile, se debe estar dentro del directorio del proyecto y ejecutar el comando `make <comando>`.

### Levantar la aplicación

```bash
make install
make up
```

