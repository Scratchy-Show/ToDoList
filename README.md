# TodoList

Improve an existing project built with Symfony 3.1 and Bootstrap 3.3.7.

------------------------------------------------------------------------------------------------------------------------------------------

## Codacy Badge
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/ecc0a8b843464aff82b74b5d82f05fbf)](https://www.codacy.com/manual/Scratchy-Show/TodoList?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Scratchy-Show/TodoList&amp;utm_campaign=Badge_Grade)

------------------------------------------------------------------------------------------------------------------------------------------
## Environment used for development

* Symfony 5.1.2

* Composer 1.9.1

* Bootstrap 3.3.7

* PHPUnit 7.5.20

* Xdebug 2.9.5

* Wampserver 3.2.0
  *   PHP 7.4.1
  *   Apache 2.4.41
  *   MySQL 8.0.18
    
------------------------------------------------------------------------------------------------------------------------------------------

## Install the project

1.  Download and install WampServer (or equivalent: MampServer, XampServer, LampServer).
2.  Download the project clone in the www folder of WampServer :
```
git clone https://github.com/Scratchy-Show/TodoList.git
```

3.  Configure the `DATABASE_URL` environment variable to connect to your database in `.env` file.
4.  **Install the dependencies** - In the root directory of the project, open the CLI (Command-Line Interface) and execute the command :
```
composer install
```

5.  **Create the database** - Execute the command :
```
php bin/console doctrine:database:create
```

6.  **Update database** - Execute the command :
```
php bin/console doctrine:schema:update --force
```

7.  **Load fixtures** - Execute the command :
```
php bin/console doctrine:fixtures:load
```

**N.B.**: If you imported tasks that are not linked to any user, the `php bin/console todolist:updatetaskanonymous` command allows you to link orphaned tasks to the Anonyme user.


8.  **Run the Symfony server** - Execute the command :
```
symfony server:start
```

9.  **Access the site** - Enter the address indicated by the web server in your browser :
```
Example: <http://127.0.0.1:8000>
```

------------------------------------------------------------------------------------------------------------------------------------------

## Tests

1.  Configure the `DATABASE_URL` environment variable to connect to your database in `.env.test` file.

2.  **Create the database** - Execute the command :
```
php bin/console doctrine:database:create --env=test
```

3.  **Update database** - Execute the command :
```
php bin/console doctrine:schema:update --env=test --force
```

4.  **Run the Symfony server** - Execute the command :
```
symfony server:start
```

5.  **Run the tests** - Execute the command :
```
php bin/phpunit
```

6.  **Check code coverage** - Execute the command :
```
php bin/phpunit --coverage-html tests/Coverage
```
