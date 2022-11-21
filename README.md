<p align="center">Alura Challenge Week 3</p>

## About Alura Challenge Week 3

This week, the challenge will be to implement an authentication mechanism in the API, so that only authenticated users can interact with it.

In addition, it will also be necessary to deploy the API on some Cloud provider, such as Heroku.

API Working on:
https://api.miguelfonseca.pt

Login API URI
https://api.miguelfonseca.pt/apilogin

Test Login:
Username: laurie81@example.org
Password: password

<hr>

<h2>Alura Challenge API Description</h2>

POST Login
https://api.miguelfonseca.pt/apilogin
json
{
  "email": "emmalee84@example.org",
  "password": "password"
}

GET Get all videos
https://api.miguelfonseca.pt/videos

GET Get all categorias
https://api.miguelfonseca.pt/categories
Authorization
Bearer Token <token>

GET Get one category
https://api.miguelfonseca.pt/categories/2
Authorization
Bearer Token <token>

GET Get an error while trying to get inexistent category
https://api.miguelfonseca.pt/categories/2322
Authorization
Bearer Token <token>

GET Get all videos
https://api.miguelfonseca.pt/videos
Authorization
Bearer Token <token>

GET Get all videos from a category
https://api.miguelfonseca.pt/categories/2/videos
Authorization
Bearer Token <token>

GET Get single video
https://api.miguelfonseca.pt/videos/2
Authorization
Bearer Token <token>

GET Get an error while trying to get inexistent video
https://api.miguelfonseca.pt/videos/2322
Authorization
Bearer Token <token>

POST Create new video
https://api.miguelfonseca.pt/videos
Authorization
Bearer Token <token>
json
{
    title
    category
    description
    url
}

POST Create new category
https://api.miguelfonseca.pt/categories
Authorization
Bearer Token <token>
json
{
  title
  color
}

POST Create new video with incomplete fields
https://api.miguelfonseca.pt/videos
Authorization
Bearer Token <token>
json
{
    description
    url
}

POST Create new category with incomplete fields
https://api.miguelfonseca.pt/categorias
Authorization
Bearer Token <token>
json
{
    title
}

PUT Update a video
https://api.miguelfonseca.pt/videos/4
Authorization
Bearer Token <token>
json
{
  "title": "Como criar uma migration no laravel.",
  "description": "Como criar uma migration no Laravel, como definir a base de dados e criar os campos a partir das migrations.",
  "url": "https://www.youtube.com/link_atualizado"
}

PUT Update a category
https://api.miguelfonseca.pt/categorias/1
Authorization
Bearer Token <token>
json
{
  "title": "Sem Categoria",
  "color": "#cfcfcf"
}

PUT Update a video with missing field
https://api.miguelfonseca.pt/videos/4
Authorization
Bearer Token <token>
json
{
  "title": "Como criar uma migration no laravel.",
  "descricao": "Como criar uma migration no Laravel, como definir a base de dados e criar os campos a partir das migrations."
}

PUT Update a category with missing field
https://api.miguelfonseca.pt/categorias/4
Authorization
Bearer Token <token>
json
{
  "title": "VB"
}

PUT Update an invalid video
https://api.miguelfonseca.pt/videos/9239
Authorization
Bearer Token <token>
json
{
  "title": "Como criar uma migration no laravel.",
  "description": "Como criar uma migration no Laravel, como definir a base de dados e criar os campos a partir das migrations."
}

PUT Update an invalid video category
https://api.miguelfonseca.pt/categorias/992
Authorization
Bearer Token <token>
json
{
  "title": "C#",
  "description": "#fff"
}

DEL Delete a video
https://api.miguelfonseca.pt/videos/7
Authorization
Bearer Token <token>

DEL Delete a category
https://api.miguelfonseca.pt/categorias/2
Authorization
Bearer Token <token>

GET Video search
https://api.miguelfonseca.pt/videos?search=a
Authorization
Bearer Token <token>
Query Params
search

