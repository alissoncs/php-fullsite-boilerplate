<?php

namespace App\Config;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class DatabaseConfig
{

  public function setup($container = null)
  {
    $capsule = new Capsule;
    $capsule->addConnection([
      'driver'    => 'mysql',
      'host'      => $_ENV['DB_HOST'],
      'database'  => $_ENV['DB_DATABASE'],
      'username'  => $_ENV['DB_USERNAME'],
      'password'  => $_ENV['DB_PASSWORD'],
      'charset'   => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'port'    => !empty($_ENV['DB_PORT']) ? $_ENV['DB_PORT'] : 3306,
      'prefix'    => !empty($_ENV['DB_PREFIX']) ? $_ENV['DB_PREFIX'] : '',
    ]);
    // $capsule->setEventDispatcher(new Dispatcher(new Container));
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
  }
}
