<?php

declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use App\Model\Config;

class ConfigTest extends TestCase {

  public function testCarbonTest() {
    $config = new Config();
    $this->assertEquals(true, $config instanceof Config);
  }

}
