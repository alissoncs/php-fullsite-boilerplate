<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Sao_Paulo');

$autoload = __DIR__ . '/../vendor/autoload.php';
// require_once ;

if (!file_exists($autoload)) {
  die("Please run 'composer install'");
}

require_once $autoload;
define('ROOT', realpath(__DIR__ . '/../'));

use Symfony\Component\Dotenv\Dotenv;
use App\App;

$dotenv = new Dotenv();
$dotfile = __DIR__ . '/../.env';

if (!file_exists($autoload)) {
  die("Create .env file in the root directory based on .env.sample file");
}


$dotenv->load($dotfile);

$app = new App();
$app->run();
