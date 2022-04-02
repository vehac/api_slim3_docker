# Slim Framework 3 Skeleton Application

Use this skeleton application to quickly setup and start working on a new Slim Framework 3 application. This application uses the latest Slim 3 with the PHP-View template renderer. It also uses the Monolog logger.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

    php composer.phar create-project slim/slim-skeleton [my-app-name]

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.

To run the application in development, you can run these commands 

	cd [my-app-name]
	php composer.phar start
	
Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:

    cd [my-app-name]
	docker-compose up -d
After that, open `http://0.0.0.0:8080` in your browser.

Run this command in the application directory to run the test suite

	php composer.phar test

That's it! Now go build something cool.

## Inicio API con Slim
- Si no existe, crear la carpeta logs en la raíz del proyecto y darle permisos de escritura.

## Docker
Docker - Slim 3 (PHP 7.1) - MariaDB - Memcached

- Para la primera vez que se levanta el proyecto con docker o se cambie los archivos de docker ejecutar:
```bash
sudo docker-compose up --build -d
```
- En las siguientes oportunidades ejecutar:

Para levantar:
```bash
sudo docker-compose start
```
Para detener:
```bash
sudo docker-compose stop
```
- Para ingresar al contenedor ejecutar:
```bash
sudo docker-compose exec webserver bash
```

- Para instalar las dependencias con composer, dentro del contenedor con docker ejecutar:
```bash
composer install
```
- Para ver el proyecto desde un navegador:

Sin virtualhost:
```bash
http://localhost:9383
```
Con virtualhost:

Si se usa Linux, agregar en /etc/hosts de la pc host la siguiente linea:
```bash
70.21.21.11    local.apislim.com
```
## MariaDB
- Si no se llegó a ejecutar el script my_db.sql, loguearse al contenedor con MariaDB:
```bash
mysql -u root -p -h70.21.21.12
7*DBslim369
```
- Luego importar el script con SOURCE <ruta_de_my_db.sql>, ejemplo:
```bash
SOURCE /var/www/html/api_slim_docker/docker/my_db.sql
```
## [Apidocjs](https://apidocjs.com)
- Se generó documentación para el api con apidocjs, para poder verla, agregar en /etc/hosts de la pc host la siguiente linea e ingresar por el navegador con local.doc-apislim.com:
```bash
70.21.21.11    local.doc-apislim.com
```
- Para clonar y construir apidocjs con docker, ejecutar los siguientes comandos:
```bash
git clone https://github.com/apidoc/apidoc.git
cd apidoc
docker build -t apidoc/apidoc .
```
- Para usar apidocjs y poder documentar el api usar el siguiente comando:
```bash
docker run --rm -v  <ruta_del_proyecto>:/home/node/apidoc apidoc/apidoc -i <ruta_de_entrada> -o <ruta_de_salida>
```
Ejemplo:
```bash
docker run --rm -v  /var/www/html/api_slim3_docker:/home/node/apidoc apidoc/apidoc -i . -o public/doc
```
## Endpoints
```bash
POST    /v1/generate-token
POST    /v1/verify-token
GET     /v1/clients
GET     /v1/clients/1
POST    /v1/clients/new
POST    /v1/clients/update/2
POST    /v1/clients/delete/2
```