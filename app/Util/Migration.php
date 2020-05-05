<?php

namespace App\Util;

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Config\DatabaseConfig;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration {
    /** @var \Illuminate\Database\Capsule\Manager $capsule */
    public $capsule;
    /** @var \Illuminate\Database\Schema\Builder $capsule */
    public $schema;

    protected function schema() {
      return $this->schema;
    }

    public function init()
    {
        $config = new DatabaseConfig();
        $this->capsule = $config->setup();

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
}
