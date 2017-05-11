Code Generator
==============

Symfony-based application that manages codes - generated random and user-provided. Allows to display, generate, add 
and delete them.

> All commands in terminal should be called from main directory of project.

# Installation

1. Clone the project

    ```bash
    $ git clone https://github.com/kniziol/code-generator.git .
    ```

2. Install packages using [Composer](https://getcomposer.org)

    ```bash
    $ composer install
    ```

> How to install Composer: https://getcomposer.org/download

3. (optional) Install assets (stylesheets, javascripts, images and other stuff)

    ```bash
    $ php bin/console assets:install --symlink
    ```
> This step is optional, because all assets will be installed automatically after installing packages by Composer (step 2)

# Configuration

1. Database connection

    After running command ```composer install``` all parameters are defined and stored in ```app/config/parameters.yml``` file. You may change those parameters.

    Example of configuration:

    ```yml
    database_host: 127.0.0.1
    database_port: null
    database_name: my_database
    database_user: my_user
    database_password: my_password
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: null
    mailer_password: null
    secret: 234ad278eqtyevg
    ```

# Database

1. Create database defined in ```app/config/parameters.yml``` configuration file

    ```bash
    $ php bin/console doctrine:database:create
    ```

2. Create tables

    ```bash
    $ php bin/console doctrine:schema:create
    ```

3. Populate tables (using DataFixtures)

     ```bash
    $ php bin/console hautelook_alice:fixtures:load --no-interaction --purge-with-truncate
    ```

# Running

Run command:

```bash
$ php bin/console server:start
```

to run built-in server or configure and run your own server (Apache, nginx or whatever serves PHP).
