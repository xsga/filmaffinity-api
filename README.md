# filmaffinity-api

[Language](https://img.shields.io/github/languages/top/xsga/filmaffinity-api)
[Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF?style=flat)](https://php.net/)
[License](https://img.shields.io/github/license/xsga/filmaffinity-api)

FilmAffinity-API is a public and non offical API wich allow you to get information about films from [FilmAffinity](http://filmaffinity.com "FilmAffinity Home") website. You can search films and get their complet  information, including cast, synopsis and cover.

The API is written in PHP and uses a JSON objects to input and output data.

## Installation

Server prerequisites:

* PHP 5.6 or later.
* Apache's `mod_rewrite` module enabled.
* Composer

Install instructions:

* Unzip the api files in an empty folder in your server.
* Make sure that the HTTP shared folder match with the api `public` folder.
* `log` folder needs read and write permissions:
```
user@server:~# chmod 777 -R log
```
* Run `composer` to install dependencies:
```
user@server:~# composer install
```
* Set up basic and mandatory api settings (`config/settingsAPI.php`):
  * Set de environment, development (dev) or production (pro)
  ```
  $settings['environment'] = 'dev';
  ``` 
  * Set de URL path (see instructions)
  ```
  $settings['url_path'] = '';
  ```

## API Public methods
The API has three main public methods:

* Search films.
* Advanced search films.
* Get film.


|Method name|API endpoint|HTTP method|Input|Output|
|-----------|------------|-----------|-----|------|
|Search films|search/do_search|POST|[search json](https://github.com/xsga/filmaffinity-api/blob/master/api/resources/schema/input/search.schema.json)|[search_results json](https://github.com/xsga/filmaffinity-api/blob/master/api/resources/schema/output/search_results.schema.json)|
|Advanced search films|search/do_adv_search|POST|[adv_search json](https://github.com/xsga/filmaffinity-api/blob/master/api/resources/schema/input/adv_search.schema.json)|[search_results json](https://github.com/xsga/filmaffinity-api/blob/master/api/resources/schema/output/search_results.schema.json)|
|Get film|film/get_film|GET|URL parameter|[film json](https://github.com/xsga/filmaffinity-api/blob/master/api/resources/schema/output/film.schema.json)|

## Basic usage

### Simple search
```
http://<server_domain_api>/search/do_search
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
http://<server_domain_api>/search/do_adv_search
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
  "year_from": "1992",
  "year_to": "2000"
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

### Get Film
```
http://<server_domain_api>/film/get_film/<film_id>
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
  "officialweb": "",
  "synopsis": "Jules y Vincent, dos asesinos a sueldo con no demasiadas luces, trabajan para el gángster Marsellus Wallace. Vincent le confiesa a Jules que Marsellus le ha pedido que cuide de Mia, su atractiva mujer. Jules le recomienda prudencia porque es muy peligroso sobrepasarse con la novia del jefe. Cuando llega la hora de trabajar, ambos deben ponerse \"manos a la obra\". Su misión: recuperar un misterioso maletín."
}
```
