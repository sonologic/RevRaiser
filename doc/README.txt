RevRaiser
=========

RevRaiser is a simple and straight-forward on-line fund raiser application. It
is written in PHP, uses a bare-boned templating engine and uses ADOdb to
connect to any database. Currently only PostgreSQL is tested, and the schema
should be adapted to other databases.

It was written to raise funds for a lasser-cutter for hackerspace Revelation
Space in The Hague, NL (https://revspace.nl/).

The latest version can be found on github:

https://github.com/sonologic/RevRaiser/

Project documentation can be found on:

https://revspace.nl/RevelationSpace/FundraiserSite

Setup
=====

Create yourself a database, and import the database schema which you can find
in doc/schema.sql.

Copy inc/settings.php to inc/local_settings.php and modify your database
settings.

Next, create your first campaign:

insert into campaign (template,admin_email,shortdesc,goal,minimum,pledge_deadline,payment_deadline) values ('example_','foo@bar.com','Example fundraiser',300000,1000,'2011-05-20','2011-07-20');

And you're good to go.

