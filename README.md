Code Generator
==============

Symfony-based application that manages codes - generated random and user-provided. Allows displaying, generating, adding
and removing them.

## Installation

1. Clone the project

    ```bash
    $ git clone git@github.com:kniziol/code-generator.git .
    ```

2. Generate self-signed SSL certificate

    ```bash
    $ cd docker/config/nginx/certificate
	$ ./generate-certificate.sh your.local.domain
    ```

Replace `your.local.domain` with the domain that you will be running application.

3. Run Docker Compose to start the project

    ```bash
    $ docker compose up -d
    ```

    If you would like to tweak ports, create `.env.local` file and enter in it some of required environment variables taht you would like to change, e.g.:

    ```
    #
    # MySQL
    #
    MYSQL_ROOT_PASSWORD=root
    MYSQL_DATABASE=application
    MYSQL_USER=user
    MYSQL_PASSWORD=user
    MYSQL_PORT=3306
    MYSQL_PORT_EXTERNAL=3306

    #
    # Nginx
    #
    NGINX_PORT=443
    NGINX_PORT_EXTERNAL=80
    ```

4. Install UI-related packages

    ```bash
    $ docker compose exec php yarn
    ```

5. Build UI-related assets

    ```bash
    $ docker compose exec php yarn dev
    ```

6. Create database

    ```bash
    $ docker compose run console doctrine:database:create
    ```

7. Create database structure

    ```bash
    $ docker compose run console doctrine:schema:create
    ```

8. (optional) Load data into database (using DataFixtures)

     ```bash
    $ docker compose run console hautelook:fixtures:load --no-interaction --purge-with-truncate
    ```

## Configuration (in the `.env` file)

1. Database connection

    ```
    DATABASE_URL=mysql://user:user@db:3306/application?serverVersion=mariadb-10.5.10
    ```

2. PHP parameters

    ```
    PHP_DATE_TIMEZONE=Europe/Warsaw
    PHP_MEMORY_LIMIT=256M
    ```

3. Default and available languages (of the UI):

    ```
    APP_DEFAULT_LOCALE=en
    APP_AVAILABLE_LOCALES=en|pl
    ```

## Tests

PHPUnit tests you can run using one of the following commands:

   ```bash
   $ docker compose run phpunit -v
   // OR
   $ docker compose exec php bin/phpunit
   ```
