<?php

// load abstract database classes
require "../src/pdo-db.class.php";
require "../src/pdo-db-base.class.php";

// load example object
require "obj-example.class.php";


// initialize database connection
$oDB=new axelhahn\pdo_db([

    // database connection
    'db'=>[
        "dsn" => "sqlite:" . __DIR__ . "/example1.sqlite3",
    ],

    // options for testing in dev environment
    'showdebug'=>true,
    'showerrors'=>true,
]);

// instanciate example object
$o=new objexample($oDB);

$o->new();
$o->set('label', 'hello');
$o->set('description', 'world');
$o->create();

print_r($o->search());
