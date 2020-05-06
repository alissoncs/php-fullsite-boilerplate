<?php
namespace App\Website;

class BaseWebsiteController {
  public function __construct($container)
  {
    $this->container = $container;
  }
}
