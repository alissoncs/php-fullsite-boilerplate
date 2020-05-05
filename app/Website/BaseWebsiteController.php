<?php
namespace App\Website;

use Psr\Container\ContainerInterface;

abstract class BaseWebsiteController
{
  protected $container;
  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }
}
