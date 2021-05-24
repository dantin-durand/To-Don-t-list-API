# To Don't List API

## Présentation

Réalisation d'une API d'authentification et de CRUD de tasks en laravel.

## Endpoints
#### Autentification
- POST /auth/login
- POST /auth/register
- POST /auth/me
- POST /auth/logout


#### CRUD Tasks
- GET /tasks
- GET /tasks/{id}
- POST /tasks
- PUT /tasks/{id}
- DELETE /tasks/{id}

## Installation

1. composer install
2. php artisan migrate
3. php artisan serve

## Démo

lien de l'API': [https://to-don-t-list.herokuapp.com/api](https://to-don-t-list.herokuapp.com/api)
