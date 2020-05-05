<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . '/../vendor/autoload.php';
define('ROOT', realpath(__DIR__ . '/../'));

use Symfony\Component\Dotenv\Dotenv;
use App\App;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$app = new App();
$app->run();
