# filmaffinity-API

[![Language](https://img.shields.io/github/languages/top/xsga/filmaffinity-api)](https://php.net/)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.4-8892BF?style=flat)](https://php.net/)
[![Latest version](https://img.shields.io/github/v/release/xsga/filmaffinity-api)](https://github.com/xsga/filmaffinity-api/releases/tag/v7.2.0)
[![License](https://img.shields.io/github/license/xsga/filmaffinity-api)](https://opensource.org/licenses/MIT)

FilmAffinity-API is a public and non offical API wich allow you to get information about films from [FilmAffinity](http://filmaffinity.com "FilmAffinity Home") website. You can search films and get their complet  information, including cast, synopsis and cover.

This API is written in PHP and uses Slim 4 framework.


### ⚠️ IMPORTANT NOTE: FilmAffinity Anti-Bot Protection ###

FilmAffinity currently implements anti-bot protection (Cloudflare or similar services like Akamai) that requires JavaScript execution and dynamically generated session cookies. This means:

- The FilmAffinity server does not return the real HTML content directly
- Instead, it serves a challenge page that your browser solves by executing JavaScript
- Session cookies are generated dynamically only after solving the challenge
- HTTP clients without a JavaScript engine (Guzzle, cURL, etc.) receive only the error/challenge page

**This is why web scrapers and API clients that don't execute JavaScript fail to retrieve film data from FilmAffinity.** To work around this limitation, you would need to use tools like:
- Puppeteer or Playwright (Node.js)
- Selenium or Playwright (Python)
- Headless browser solutions with JavaScript rendering capabilities
- Third-party scraping services: [Prerender.io](https://prerender.io), [ScraperAPI](https://www.scraperapi.com), [Apify](https://apify.com), or similar services that handle JavaScript rendering and bot protection challenges


## Technology Stack & Dependencies

**Core Framework & HTTP:**
- [Slim 4](https://www.slimframework.com/) - Lightweight PHP microframework
- [Guzzle HTTP Client](https://docs.guzzlephp.org/) - PHP HTTP client for making requests

**Database & ORM:**
- [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html) - Object-Relational Mapping
- MySQL database

**Dependency Injection & Configuration:**
- [PHP-DI](https://php-di.org/) - Container for dependency injection
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) - Environment variables management

**Authentication & Security:**
- [Firebase JWT](https://github.com/firebase/php-jwt) - JWT token handling for API authentication

**Validation & Schema:**
- [Swaggest JSON Schema](https://github.com/swaggest/json-schema) - JSON schema validation

**Console & Commands:**
- [Symfony Console](https://symfony.com/doc/current/components/console.html) - CLI commands framework

**Caching:**
- [Symfony Cache](https://symfony.com/doc/current/components/cache.html) - Application caching

---

## Install with Docker Compose

Prerequisites:

* Docker.
* Docker Desktop (optional)

Instructions:

* Run `docker compose` to star Docker container and inits system.
```
docker compose up -d
```
* Run `composer install` to install the project dependencies:
```
composer install
```
* Rename `config/.env.example` to `config/.env` to activates environment settings.
* Setup API settings edditing `config/.env` file (don't change `URL_PATH` and database variables).
* Use test user (email `test@test.com` and password `test`) or create a user using command `app:create-user`:
```
docker exec -it filmaffinityapi-web-server php .bin/console app:create-user
```
* FilmAffinityAPI is available at the following URL: `http://localhost/app`


## Manual installation

Prerequisites:

* PHP 8.3 or later.
* PHP libraries `mbstring`,  `zip`, `gd`, `pdo_mysql`, `sockets` and `apcu` installed.
* Apache's `mod_rewrite` and `mod_headers` modules enabled.
* Composer

Instructions:

* Unzip the API files in an empty folder in your server.
* Make sure that the HTTP shared folder match with the API `public` folder.
* `log`, `tmp` and `data` folders and subfolders needs read and write permissions:
```
chmod 777 -R log
chmod 777 -R tmp
chmod 777 -R data
```
* Run `composer install` to install the project dependencies:
```
composer install
```
* Rename `config/.env.example` to `config/.env` to activates environment settings.
* Setup API settings edditing `config/.env` file.
* Create a MySQL database and run scripts under folder `scripts` to create database structure.
* Create a user using command `app:create-user`:
```
php .bin/console app:create-user
```


## API public methods

The API has the following public methods:

|Method name|HTTP method|API endpoint|Body|
|-----------|-----------|------------|----|
|Get user token|POST|users/token|[JSON Schema](https://github.com/xsga/filmaffinity-api/blob/master/config/schemas/input/get.token.schema.json)|
|Simple films search|POST|searches/simple|[JSON Schema](https://github.com/xsga/filmaffinity-api/blob/master/config/schemas/input/simple.search.schema.json)|
|Advanced films search|POST|searches/advanced|[JSON Schema](https://github.com/xsga/filmaffinity-api/blob/master/config/schemas/input/advanced.search.schema.json)|
|Get film information|GET|films/:id|-|
|Get genres|GET|genres|-|
|Get countries|GET|countries|-|


## Console commands

System provides some Symfony console commands:

|Command|Command description|
|-------|------------|
`app:create-user`|Create a new user|
`app:delete-user`|Delete a user|
`app:disable-user`|Disable a user|
`app:enable-user`|Enable a user|
`app:get-password`|Get hashed password|
`app:get-token`|Get JWT user token|
`app:backup-countries`|FilmAffinity countries backup (store in `data/backup` folder)|
`app:backup-genres`|FilmAffinity genres backup (store in `data/backup` folder)|

To executes a command, run the following command:
```
php .bin/console <COMMAND>
```

In a docker environment, execute it into container:
```
docker exec -it filmaffinityapi-web-server php .bin/console <COMMAND>
```

## API basic usage examples

### Simple films search
**URL**
```
[GET] http://<server_domain_api>/searches/simple
```

**INPUT**
```json
{"text": "pulp fiction"}
```

**OUTPUT**
```json
{
  "status": "OK",
  "statusCode": 200,
  "response": {
    "total": 2,
    "results": [
      {
        "id": 160882,
        "title": "Pulp Fiction",
        "year": 1994,
        "directors": ["Quentin Taratino"]
      },
      {
        "id": 991349,
        "title": "8 Bit Cinema: Pulp Fiction",
        "year": 2014,
        "directors": ["David Dutton"]
      }
    ]
  }
}
```

### Advanced films search

**EXAMPLE 1:** An example of an advanced search searching only for a text in the title of the film.

**URL**
```
[POST] http://<server_domain_api>/searches/advanced
```

**INPUT**
```json
{
  "text": "pulp fiction",
  "search_in_title": true,
  "search_in_director": false,
  "search_in_cast": false,
  "search_in_screenplay": false,
  "search_in_photography": false,
  "search_in_soundtrack": false,
  "search_in_producer": false,
  "country": "",
  "genre": "",
  "year_from": 0,
  "year_to": 0
}
```

**OUTPUT**
```json
{
  "status": "OK",
  "statusCode": 200,
  "response": {
    "total": 1,
    "results": [
      {
        "id": 160882,
        "title": "Pulp Fiction",
        "year": 1994,
        "directors": ["Quentin Taratino"]
      },
      {
        "id": 991349,
        "title": "8 Bit Cinema: Pulp Fiction",
        "year": 2014,
        "directors": ["David Dutton"]
      }
    ]
  }
}
```

**EXAMPLE 2:** another example of an advanced search searching for a text in the title of the film and between two years.

**URL**
```
[POST] http://<server_domain_api>/searches/advanced
```

**INPUT**
```json
{
  "text": "pulp fiction",
  "search_in_title": true,
  "search_in_director": false,
  "search_in_cast": false,
  "search_in_screenplay": false,
  "search_in_photography": false,
  "search_in_soundtrack": false,
  "search_in_producer": false,
  "country": "",
  "genre": "",
  "year_from": 1992,
  "year_to": 2000
}
```

**OUTPUT**
```json
{
  "status": "OK",
  "statusCode": 200,
  "response": {
    "total": 1,
    "results": [
      {
        "id": 160882,
        "title": "Pulp Fiction",
        "year": 1994,
        "directors": ["Quentin Taratino"]
      }
    ]
  }
}
```

### Get film information
**URL**
```
[GET] http://<server_domain_api>/films/160882
```

**OUTPUT**
```json
{
  "status": "OK",
  "statusCode": 200,
  "response": {
    "filmAfinityId": "160882",
    "title": "Pulp Fiction",
    "originalTitle": "Pulp Fiction",
    "year": 1994,
    "duration": 153,
    "coverUrl": "https://pics.filmaffinity.com/pulp_fiction-210382116-large.jpg",
    "coverFile": "pulp_fiction-210382116-large.jpg",
    "rating": "8,6",
    "country": "Estados Unidos",
    "directors": ["Quentin Tarantino"],
    "screenplay": "Quentin Tarantino, Roger Avary",
    "soundtrack": "Varios",
    "photography": "Andrzej Sekula",
    "cast": ["John Travolta", "Samuel L. Jackson", "Uma Thurman", "Bruce Willis", "Ving Rhames", "Harvey Keitel", "Tim Roth", "Amanda Plummer", "María de Medeiros", "Eric Stoltz", "Rosanna Arquette", "Christopher Walken", "Paul Calderon", "Bronagh Gallagher", "Peter Greene", "Stephen Hibbert", "Angela Jones", "Phil LaMarr", "Robert Ruth", "Julia Sweeney", "Quentin Tarantino", "Frank Whaley", "Duane Whitaker", "Steve Buscemi", "Burr Steers"],
    "producer": "Miramax, Band Apart, Jersey Films. Lawrence Bender",
    "genres": ["Thriller"],
    "genreTopics": ["Crimen", "Historias cruzadas", "Película de culto", "Comedia negra"],
    "synopsis": "Jules y Vincent, dos asesinos a sueldo con no demasiadas luces, trabajan para el gángster Marsellus Wallace. Vincent le confiesa a Jules que Marsellus le ha pedido que cuide de Mia, su atractiva mujer. Jules le recomienda prudencia porque es muy peligroso sobrepasarse con la novia del jefe. Cuando llega la hora de trabajar, ambos deben ponerse \"manos a la obra\". Su misión: recuperar un misterioso maletín."
  }
}
```

### Get genres
**URL**
```
[GET] http://<server_domain_api>/genres
```

**OUTPUT**
```json
{
  "status": "OK",
  "statusCode": 200,
  "response": [
    {
      "code": "AC",
      "description": "Acción"
    },
    {
      "code": "AN",
      "description": "Animación"
    },
    {
      "code": "AV",
      "description": "Aventuras"
    },
    {
      "code": "BE",
      "description": "Bélico"
    }
  ]
}
```

### Get countries
**URL**
```
[GET] http://<server_domain_api>/countries
```

**OUTPUT**
```json
{
  "status": "OK",
  "statusCode": 200,
  "response": [
    {
      "code": "AF",
      "name": "Afganistán"
    },
    {
      "code": "AL",
      "name": "Albania"
    },
    {
      "code": "DE",
      "name": "Alemania"
    },
    {
      "code": "FD",
      "name": "Alemania del Este (RDA)"
    }
  ]
}
```

You must inform the country/genre code in the body request of advances search if you want use these filters.
