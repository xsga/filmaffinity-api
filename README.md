# filmaffinity-API

[![Language](https://img.shields.io/github/languages/top/xsga/filmaffinity-api)](https://php.net/)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.0-8892BF?style=flat)](https://php.net/)
[![Latest version](https://img.shields.io/github/v/release/xsga/filmaffinity-api)](https://github.com/xsga/filmaffinity-api/releases/tag/v3.1.0)
[![Workflow Status](https://img.shields.io/github/workflow/status/xsga/filmaffinity-api/PHP%20Composer)](https://github.com/xsga/filmaffinity-api/actions?query=workflow%3A%22PHP+Composer%22)
[![License](https://img.shields.io/github/license/xsga/filmaffinity-api)](https://opensource.org/licenses/MIT)

FilmAffinity-API is a public and non offical API wich allow you to get information about films from [FilmAffinity](http://filmaffinity.com "FilmAffinity Home") website. You can search films and get their complet  information, including cast, synopsis and cover.

This API is written in PHP and uses a JSON objects to input and output data.

## Installation

Server prerequisites:

* PHP 8.0 or later.
* Apache's `mod_rewrite` module enabled.
* Composer

Install instructions:

* Unzip the API files in an empty folder in your server.
* Make sure that the HTTP shared folder match with the API `public` folder.
* `log` folder needs read and write permissions:
```
user@server:~# chmod 777 -R log
```
* `tmp` folder needs read and write permissions:
```
user@server:~# chmod 777 -R tmp
```
* Run `composer` to install the project dependencies:
```
user@server:~# composer install
```
* Rename `config/.env.example` to `config/.env` to activates common environment settings.
* Rename `config/.dev.env.example` to `config/.dev.env` to activates development environment settings.
* Rename `config/.pro.env.example` to `config/.pro.env` to activates production environment settings.
* Setup API settings:
  * `config/.env` for commons API settings.
  * `config/.dev.env` for development API settings.
  * `config/.pro.env` for production API settings.

## API Public methods
API has four main public methods:

* Search films.
* Advanced search films.
* Get film information.
* Get resources (countries and genres)


|Method name|API endpoint|HTTP method|Input|Output|
|-----------|------------|-----------|-----|------|
|Search films|search/simple|POST|[search json](https://github.com/xsga/filmaffinity-api/blob/master/src/filmaffinity/resources/schema/input/search.schema.json)|[search_results json](https://github.com/xsga/filmaffinity-api/blob/master/src/filmaffinity/resources/schema/output/search_results.schema.json)|
|Advanced search films|search/advanced|POST|[adv_search json](https://github.com/xsga/filmaffinity-api/blob/master/src/filmaffinity/resources/schema/input/adv_search.schema.json)|[search_results json](https://github.com/xsga/filmaffinity-api/blob/master/src/filmaffinity/resources/schema/output/search_results.schema.json)|
|Get film|films/:id|GET|URL parameter|[film json](https://github.com/xsga/filmaffinity-api/blob/master/src/filmaffinity/resources/schema/output/film.schema.json)|
|Get resources|resources/:name|GET|URL parameter|[Various](https://github.com/xsga/filmaffinity-api/blob/master/src/filmaffinity/resources/json)|

## Basic usage

### Simple search
```
http://<server_domain_api>/search/simple
```

INPUT
```json
{"text": "pulp fiction"}
```

OUTPUT
```json
{
  "total": 2,
  "results": [{
    "id": 160882,
    "title": "Pulp Fiction (1994)"
  }, {
    "id": 991349,
    "title": "8 Bit Cinema: Pulp Fiction (C) (2014)"
  }]
}
```

### Advanced search
```
http://<server_domain_api>/search/advanced
```

INPUT
```json
{
  "text": "pulp fiction",
  "title": true,
  "director": false,
  "cast": false,
  "screenplay": false,
  "photography": false,
  "soundtrack": false,
  "producer": false,
  "country": "",
  "genre": "",
  "year_from": 1992,
  "year_to": 2000
}
```

OUTPUT
```json
{
  "total": 1,
  "results": [{
    "id": 160882,
    "title": "Pulp Fiction (1994)"
  }]
}
```

### Get film information
```
http://<server_domain_api>/films/:id
```

OUTPUT
```json
{
  "filmAfinityId": 160882,
  "title": "Pulp Fiction",
  "originalTitle": "Pulp Fiction",
  "year": "1994",
  "duration": "153",
  "coverUrl": "https:\/\/pics.filmaffinity.com\/pulp_fiction-210382116-large.jpg",
  "coverFile": "pulp_fiction-210382116-large.jpg",
  "rating": "8,6",
  "country": "Estados Unidos",
  "directors": ["Quentin Tarantino"],
  "screenplay": "Quentin Tarantino, Roger Avary",
  "soundtrack": "Varios",
  "photography": "Andrzej Sekula",
  "cast": ["John Travolta", "Samuel L. Jackson", "Uma Thurman", "Bruce Willis", "Ving Rhames", "Harvey Keitel", "Tim Roth", "Amanda Plummer", "María de Medeiros", "Eric Stoltz", "Rosanna Arquette", "Christopher Walken", "Paul Calderon", "Bronagh Gallagher", "Peter Greene", "Stephen Hibbert", "Angela Jones", "Phil LaMarr", "Robert Ruth", "Julia Sweeney", "Quentin Tarantino", "Frank Whaley", "Duane Whitaker", "Steve Buscemi", "Burr Steers"],
  "producer": "Productor: Lawrence Bender. Miramax, Band Apart, Jersey Films",
  "genres": ["Thriller", "Crimen", "Historias cruzadas", "Película de culto", "Comedia negra"],
  "synopsis": "Jules y Vincent, dos asesinos a sueldo con no demasiadas luces, trabajan para el gángster Marsellus Wallace. Vincent le confiesa a Jules que Marsellus le ha pedido que cuide de Mia, su atractiva mujer. Jules le recomienda prudencia porque es muy peligroso sobrepasarse con la novia del jefe. Cuando llega la hora de trabajar, ambos deben ponerse \"manos a la obra\". Su misión: recuperar un misterioso maletín."
}
```

### Get resources

```
http://<server_domain_api>/resources/:name
```

Allowed values for `:name`:

* `countries` - List of allowed countries.
* `genres` - List of allowed genres.

You must inform the country/genre code in the body request of advances search if you want use these filters.
