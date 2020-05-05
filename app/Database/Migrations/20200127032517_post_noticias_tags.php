<?php

use \App\Util\Migration;

class PostNoticiasTags extends Migration
{
    public function change()
    {

      $this->schema->create('noticias_tags', function ($table) {
        $table->increments('id');

        $table->integer('tag_id')->unsigned()->index();
        $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

        $table->integer('noticia_id')->unsigned()->index();
        $table->foreign('noticia_id')->references('id')->on('noticias')->onDelete('cascade');
      });

    }
}
