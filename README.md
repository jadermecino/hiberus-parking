## Installation

Parking API requires Docker latest version, Php 8+, MariaDB 10+, Redis 6+, Nginx 1.25+ to run.

Install the dependencies, import database and start.

```sh
make install
make migrate-database
make start
```

Verify the deployment by navigating to your server address in your preferred browser.

```sh
http://127.0.0.1
```

Or

```sh
http://localhost
```

## Developer

For development require Lando 3.21+...

- First create **.env** file from **.env.example**
- Change value for **"DRUPAL_DATABASES_DEFAULT_DATABASE"** to "drupal10"
- Change value for **"DRUPAL_DATABASES_DEFAULT_USER"** to "drupal10"
- Change value for **"DRUPAL_DATABASES_DEFAULT_PASSWORD"** to "drupal10"

Turn on lando :)

```sh
lando start
lando composer install
lando db-import database/database.sql
lando drush cr
```

Navigate for the lando url's published on console.
