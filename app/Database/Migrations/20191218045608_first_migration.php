<?php
use \App\Util\Migration;

class FirstMigration extends Migration
{
    public function down() {
      $this->schema->dropIfExists('usuarios');
      $this->schema->dropIfExists('configs');

      $this->schema->dropIfExists('banners');
    }

    public function up()
    {
      $this->schema->create('usuarios', function ($table) {
        $table->increments('id');
        $table->string('email')->unique();
        $table->string('login')->unique();
        $table->string('senha');
        $table->boolean('ativo');
        $table->timestamps();
      });

      $this->schema->create('configs', function ($table) {
        $table->increments('id');
        $table->string('key')->unique();
        $table->string('value')->nullable();
        $table->string('type');
        $table->timestamps();
      });


      $this->schema->create('banners', function ($table) {
        $table->increments('id');
        $table->string('titulo');
        $table->string('posicao')->nullable();
        $table->string('sub_titulo')->nullable();
        $table->string('resumo')->nullable();
        $table->string('url')->nullable();
        $table->boolean('nova_aba')->default(false);
        $table->string('imagem')->nullable();
        $table->string('imagem_mobile')->nullable();
        $table->string('descricao')->nullable();
        $table->string('btn_titulo')->nullable();
        $table->string('btn_icon')->nullable();
        $table->string('status');
        $table->integer('ordem')->default(0);

        $table->timestamps();
      });

    }
}
