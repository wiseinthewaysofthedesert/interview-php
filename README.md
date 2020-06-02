# Interview Exercise

Requirements:
    - PHP 7.4.X
    - Docker & Docker Compose (NOTE: If you are on Mac installing Docker Desktop https://docs.docker.com/docker-for-mac/install/ will install both, if you are on Windows you will need to install them separately https://docs.docker.com/compose/install/) 
    - Composer (https://getcomposer.org/download/)

## Installation

- Clone the git repository
- Navigate to the directory and run 'php composer.phar install' to install all dependencies

## Run

- Run 'docker-compose build'
- Then run 'docker-compose up'
- The web app should not be running in a docker container!

## Server side rendered frontend
Navigate to
- `http://localhost:8082/api/index.php` to view the app

## API
Use these addresses for the various API calls you make.

Make POST requests to:
- `POST http://localhost:8082/api/converter.php`
- Example details:
```
    POST ?from=EUR&to=YEN&amount=10.22
```
Make GET requests to:
- `GET http://localhost:8082/api/history.php` (returns json)
- `GET http://localhost:8082/api/history.php?clear` (clear history, 200 OK)

## MYSQL Details
- By default a Docker container running MYSQL has been created to allow creation of a database as part of the exercise. 
- To open a connection to MYSQL you can use these details:
- 'User: test & Password: password'

