<?php
$autoload = __DIR__ . '/../vendor/autoload.php';
// require_once ;

if (!file_exists($autoload)) {
  die("Please run 'composer install'");
}

require_once $autoload;
define('ROOT', realpath(__DIR__ . '/../'));
