<?php 
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$config = $app->getContainer()->get('config');

$capsule->addConnection([

  'driver' => $config->get('db.driver'),
  'host' => $config->get('db.host'),
  'database' => $config->get('db.name'),
  'username' => $config->get('db.username'),
  'password' => $config->get('db.password'),
  'charset' => $config->get('db.charset'),
  'collation' => $config->get('db.collation'),
  'prefix' => $config->get('db.prefix'),
]);

$capsule->bootEloquent();