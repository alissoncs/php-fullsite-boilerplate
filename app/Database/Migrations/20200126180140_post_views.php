<?php

use \App\Util\Migration;

class PostViews extends Migration
{
  public function change()
  {
    $this->schema->table('noticias', function ($table) {
      $table->integer('views')->nullable()->default(0);
    });

    $this->schema->table('posts', function ($table) {
      $table->integer('views')->nullable()->default(0);
    });

  }
}
