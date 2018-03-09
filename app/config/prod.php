<?php

// Doctrine (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'charset'  => 'utf8',
    'host'     => 'db688128399.db.1and1.com',
    'port'     => '3306',
    'dbname'   => 'db688128399',
    'user'     => 'dbo688128399',
    'password' => '22124215',
);

// define log parameters
$app['monolog.level'] = 'WARNING';