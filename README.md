
# LibraryManager
A small symfony 5.4 app to manage books

## Get Project &  Use it yourself 

First **retrieve [Composer](https://getcomposer.org/download/ "Composer")**
Once installed, check the **presence** of **composer.json** at the **root** of the **project** and **run** the **following command** in a command prompt:
``composer install``


With you favorite text editor or IDE, open:
``.env``

Go to and edit with your credentials for database:
```php
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
# DATABASE_URL="mysql://username:!password!@127.0.0.1:3306/database?serverVersion=8&charset=utf8mb4"
DATABASE_URL="postgresql://username:!password!@127.0.0.1:5432/database?serverVersion=15&charset=utf8"
```

After that, run in a command prompt:
`` php bin/console doctrine:database:create``
`` php bin/console doctrine:migrations:migrate``

Run:
`` php bin/console doctrine:fixtures:load``
__________________
To make the project in dev environment:

Copy the file ``.env`` and rename it  to ``.env.local``

and change 
```php
APP_ENV=prod
```
to 
```php
APP_ENV=dev
```

### Now you can launch your server and start to enjoy a library manager!

Admin credentials: 
``admin@test.test``
password: 
``password``
