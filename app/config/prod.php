<?php

// Doctrine (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'charset'  => 'utf8',
    'host'     => '',
    'port'     => '',
    'dbname'   => '',
    'user'     => '',
    'password' => '',
);

// define log parameters
$app['monolog.level'] = 'WARNING';
