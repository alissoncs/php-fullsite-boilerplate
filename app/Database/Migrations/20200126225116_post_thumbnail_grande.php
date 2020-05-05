<?php

use \App\Util\Migration;

class PostThumbnailGrande extends Migration
{
    public function change()
    {
      $this->schema->table('noticias', function ($table) {
        $table->string('thumbnail_detalhe')->nullable();
      });

      $this->schema->table('posts', function ($table) {
        $table->string('thumbnail_detalhe')->nullable();
      });
    }
}
