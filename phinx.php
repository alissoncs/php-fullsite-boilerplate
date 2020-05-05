<?php

require_once __DIR__ . '/vendor/autoload.php';
define('ROOT', realpath(__DIR__ . '/../'));

use Symfony\Component\Dotenv\Dotenv;
use App\App;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . DIRECTORY_SEPARATOR . '.env');

return [
  'paths'        => [
    'migrations' => '%%PHINX_CONFIG_DIR%%/app/Database/Migrations',
    'seeds'      => '%%PHINX_CONFIG_DIR%%/app/Database/Seeds',
  ],
  'migration_base_class' => '\App\Util\Migration',
  'seed_base_class' => '\App\Util\Seeder',

  'environments' => [
    'default_migration_table' => 'phinxlog',
    'default_database' => 'dev',
    'dev' => [
      'adapter' => 'mysql',
      'host'      => $_ENV['DB_HOST'],
      'name'  => $_ENV['DB_DATABASE'],
      'user'  => $_ENV['DB_USERNAME'],
      'pass'  => $_ENV['DB_PASSWORD'],
    ]
  ]
];
