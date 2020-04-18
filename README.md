
# Starter setup for Symfony 4 and Angular 6 Dockerized Project
Docker compose for symfony + mysql + angular project



## **Presentation**

This is a docker composed project that can be used to quickly start a SF4 Angular 6 Project.
Cloned https://github.com/tzgued/Docker-PHP-Symfony-Angular project and customized it to my needs.
PS:

 - The ports used in the [docker-compose.yml](https://github.com/tzgued/dockerPHP/blob/master/docker-compose.yml) maybe not the ones you would love to setup expecially the NGINX on port 81.
 - For developping on Angular I prefer to [ng-serve](https://github.com/angular/angular-cli/wiki/serve) on my machine. It is perfectly fine if you want to add another container based on node and run your dev on it. I may push another version with that.
 - It's my first time doing such contribution to the github community, so feel free to interact in any way you like.

## Docker containers:

		DataBase:
		 1. MySQL
		 2. PhpMyAdmin
		
		Server Code:
		 1. PHP
		 2. Apache
	 
		 Front End Code:
		 1. NGINX


Usage
-----
Build and Run development environment
```bash
$ docker-compose up --force-recreate --build
```
To down environment
```bash
$ docker-compose down
```
Show your containers list
```bash
$ docker-compose ps -a
```

PHP service steps to do first time
-----
To run some commands in our PHP Service
```bash
$ docker-compose exec php bash
```
Navigate to your symfony project path
```bash
$ cd /home/wwwroot/sf4
```
You may need to install your vendors
```bash
$ composer install
```

Database configuration
-----
You can create the Mysql Database manually under Phpmyadmin: http://localhost:8080

Make sure you set the same database name as in .env file

I use Postman to insert some data to DB with http POST method:
http://127.0.0.1:82/api/commande/post

{
	"ref":"newProject11event",
	"date":"2014-08-26T22:37:37Z"
}


Hacks
-----
For correct work with angular app you must fix `package.json`
```
"scripts": {
    "ng": "ng",
    "start": "ng serve --host 0.0.0.0",
    ....
```

Access to projects
------------------
Symfony: http://localhost:82/api/commande/list (SF api endpoint, you can test it from your browser)

Angular: http://localhost:4200

Phpmyadmin: http://localhost:8080

For CORS issue, you can install CORS extension in your browser, and enable it so that you can test the project


Enjoy your day =D
