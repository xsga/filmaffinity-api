# filmaffinity-api

FilmAffinity-API it's a public API wich allow you to get information about films from [FilmAffinity](http://filmaffinity.com "FilmAffinity Home") website. You can search films and get their complet  information, including cast, synopsis and cover.

The API is written in PHP and uses a JSON objects as a input and output data.

## Installation
***
TODO.

## Public methods
***
These are the public methods:

* Search films.
* Advanced search films.
* Get film.
* Get film genres.
* Get countries.

|Method name|API endpoint|HTTP method|Input|Output|
|-----------|------------|-----------|-----|------|
|Search films|search/do_search|POST|JSON object|JSON object|
|Advanced search films|search/do_adv_search|POST|JSON object|JSON object|
|Get film|film/get_film|GET|URL parameter|JSON object|
|Get film genres|resources/get_genres|GET|none|JSON object|
|Get countries|resources/get_countries|GET|none|JSON object|

### Public method Search
***
This public method allows you to search films by a single text.

<ins>API ENDPOINT</ins>
```
http://<server_domain_api>/search/do_search
```

<ins>INPUT</ins>
```json
{"text": "pulp fiction"}
```
JSON properties:

* "text" - text to search, mandatory.

<ins>OUTPUT</ins>
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
JSON properties:

* "total" - number of search results, integer.
* "results" - list of single results.
  * "id" - FilmAffinity film ID, integer.
  * "title" - film title and release year.

### Public method Advanced Search
***
This public method allows you to search films by some criterias.

<ins>API ENDPOINT</ins>
```
http://<server_domain_api>/search/do_adv_search
```

<ins>INPUT</ins>
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
<ins>OUTPUT</ins>
```json
{
  "total": 1,
  "results": [{
    "id": 160882,
    "title": "Pulp Fiction (1994)"
  }]
}
```
### Public method Get Film
***
This public method allows you to get film information by id (FilmAffinity ID).

<ins>API ENDPOINT</ins>
```
http://<server_domain_api>/film/get_film/<film_id>
```

<ins>OUTPUT</ins>
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
