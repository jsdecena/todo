# Simple Todo App

[![Build Status](https://travis-ci.org/jsdecena/todo.svg?branch=master)](https://travis-ci.org/jsdecena/todo)

## Installation

- Clone (or fork) this repository


## Set up API

- Change to `api` directory and run `composer install`

- Create `todo.sqlite` in the `/api/database` directory

- Migrate initial database tables: `php artisan migrate`

- Serve the API server with: `php artisan serve`

- API server will be at [http://127.0.0.1:8000](http://127.0.0.1:8000)

- Optional: Run unit tests with `vendor/bin/phpunit`. Every push on this app will trigger a build in [TravisCI](https://travis-ci.org/jsdecena/todo)

---

## Set the UI

- Change to the `todo` directory

- Install packages with `npm install`

- Serve the UI with `npm run serve`

- UI app will be served in [http://localhost:8080/](http://localhost:8080/)