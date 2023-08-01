ToDoList
========

projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

## Environnement de développement

### Prérequis

    * PHP 8.1
    * Composer
    * Symfony CLI
    * Docker
    * Docker-compose

Pour verifier les prérequis :

 ```bash
    symfony check:requirements
 ```
## Install
### Download or clone the repository

 ```bash
    git clone https://github.com/nbahire/Projet8_todo.git
 ```
### Install docker
 ```bash
    docker compose up -d
 ```
### Download dependencies
 ```bash
    composer install
 ```
### Create database

 ```bash
    php bin/console doctrine:database:create
 ```
### Load Fixtures

 ```bash
    php bin/console doctrine:fixtures:load
 ```
### Link for the project
 ```bash
    http://localhost:8888/
 ```
### to see the coverage
 ```bash
    var/coverage/index.html
 ```
