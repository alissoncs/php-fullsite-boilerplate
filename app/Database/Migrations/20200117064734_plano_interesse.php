<?php

use \App\Util\Migration;

class PlanoInteresse extends Migration
{
    public function change()
    {

      $this->schema->table('plano_interesses', function ($table) {
        $table->string('plano_whatsapp_click')->nullable();
        $table->string('status')->nullable();
      });

    }
}
