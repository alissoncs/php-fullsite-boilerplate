<?php


use Phinx\Seed\AbstractSeed;

class Demo extends AbstractSeed
{
  public function getDependencies()
  {
    return [
      'AdminUsers'
    ];
  }
  public function run()
  {



  }
}
