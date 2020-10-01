# Quote-machine

Quote-machine is an application to show different quote. 


Example of features:

* CRUD of quotes
* Display of a random quote
* Category
* Rights system
* Import from command line

## Requirements

* PHP 7.2.9 or higher;
* PDO-SQLite PHP extension enabled;
* and the usual Symfony application requirements.

## Installation

Use the package manager [git](https://github.com/) to install quote-machine.

```bash
$ git clone https://iut-info.univ-reims.fr/gitlab/anto0037/quote_machine.git
```

## Usage

There's no need to configure anything to run the application. If you have installed Symfony, run this command and access the application in your browser at the given URL (https://localhost:8000 by default):

```bash
$ cd quote_machinemy_project/
$ composer install
$ symfony serve
```
If you don't have the Symfony binary installed, run php -S localhost:8000 -t public/ to use the built-in PHP web server or [configure a web server](https://symfony.com/doc/current/setup/web_server_configuration.html) like Nginx or Apache to run the application.

## Data_Base

   ##### Creation of the database
    
Create a .env.local file and integrate the information necessary to connect to your localhost.

Modify db_user and db_password by your database connection login.
```
DATABASE_URL=mysql://db_user:db_password@localhost:3306/quote_machine?serverVersion=5.7
```

Create a new database: Quote_machine

```bash
$ bin/console doctrine:database:create
```

   ##### Database script 
    
Generate the database with Doctrine :

```bash
$ bin/console doctrine:schema:create
```
   ##### Loading Fixtures

Load fixtures by executing this command : 

```bash
$ php bin/console doctrine:fixtures:load
```


## License
[IUT de Reims](https://www.iut-rcc.fr/)