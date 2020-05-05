<?php

use \App\Util\Migration;

class EspecialidadeLogica extends Migration
{
    public function change()
    {

      $this->schema->create('mapeamento_especialidades', function ($table) {
        $table->increments('id');
        $table->string('codigo_cbo');
        $table->string('descricao')->nullable();
        $table->string('subespec')->nullable();
        $table->text('tags_busca')->nullable();
      });

    }
}
