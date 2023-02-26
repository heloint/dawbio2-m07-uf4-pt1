# Provençana - DAWBIO-2 Module 7 (backend programming) UF4-PT1 (PHP/Laravel)
## (Project of UF4: Football team and player manager web app)

## Author: Daniel Majer
## [Github repository](https://github.com/heloint/dawbio2-m07-uf4-pt1)


# INITIAL SETUP

### 1. Init the DB.

```bash
mariadb -u <your_user> -p < ./init-app-db.sql
```

*This will initialize the database and the user for the application.*

---

### 2. From the ./football directory.

```bash
composer install
```

* Install packages of Laravel.

```bash
php artisan migrate
```

```bash
php artisan db:seed
```

*This will create the required tables and fetch random data to them*

---

### 3. Start up the Laravel server of the application

```bash
php artisan serve
```
