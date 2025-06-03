# Laravel-app

Welcome to small laravel-app made for tests.
PHP : 8.1
Laravel : 10.x

## Pre-requisite
Have a MySQL (version used for development was 8.0.30) with 2 schemas (database) :
`laravel_test` and `laravel_unit_test`

## Installation

Run the migrations
```bash
php artisan migrate
```
Populate the database
```bash
php artisan db:seed
```
Run the migrations for the test schema
```bash
php artisan migrate --database='mysql_testing'
```

## API Routes

App url is laravel-app.test but you can set it up as you wish

```python
# Authentication
POST     api/login
POST     api/logout

# Profiles
GET      api/profiles
POST     api/profiles
PUT      api/profiles/{id}
DELETE   api/profiles/{id}

# Comments
GET      api/profiles/{id}/comments
POST     api/profiles/{id}/comments
PUT      api/comments/{id}
DELETE   api/comments/{id}
```

## Authors
Abény GIVAUDAN
