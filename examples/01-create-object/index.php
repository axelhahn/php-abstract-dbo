<?php

echo "<pre>

----------------------------------------------------------------------

EXAMPLE 1: CREATE OBJECT

----------------------------------------------------------------------

";

// ----------------------------------------------------------------------
echo  "----- Load requirements...\n";
// load abstract database classes
require "../../src/pdo-db.class.php";
require "../../src/pdo-db-base.class.php";

// load example object
require "obj-example.class.php";

echo  "✅ OK.\n\n";


// ----------------------------------------------------------------------
echo  "----- Initialize database...\n";
$oDB=new axelhahn\pdo_db([
    'db'=>[
        "dsn" => "sqlite:" . __DIR__ . "/example1.sqlite3",
    ],
    'showdebug'=>true,
    'showerrors'=>true,
]);
echo  "✅ OK.\n\n";


// ----------------------------------------------------------------------
echo  "----- Initialize object...\n";
// instanciate example object
$o=new objexample($oDB);
echo  "✅ OK.\n\n";


// ----------------------------------------------------------------------
echo  "----- Create new item...\n";
$o->new();
$o->set('label', 'hello');
$o->set('description', 'world');
echo  "✅ OK.\n\n";


echo  "----- Store in database...\n";
if($o->create()){
    echo  "✅ OK.\n\n";
} else {
    echo  "❌ ERROR.\n\n";
    exit(1);
}

echo  "----- Database search...\n";
print_r($o->search());

echo "\n\nDone.";

// ----------------------------------------------------------------------
