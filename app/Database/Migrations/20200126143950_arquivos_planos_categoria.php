<?php

use \App\Util\Migration;

class ArquivosPlanosCategoria extends Migration
{
    public function change()
    {
      $this->schema->table('plano_categorias', function ($table) {
        $table->text('arquivos')->nullable();
      });

    }
}
