<?php

use \App\Util\Migration;

class EncontreSeuPlano extends Migration
{
    public function change()
    {
      $this->schema->table('planos', function ($table) {
        $table->boolean('encontre_exibir')->default(true)->nullable();
        $table->integer('encontre_exibir_ordem')->default(0)->nullable();
        $table->boolean('atencao_primaria')->default(true)->nullable();
        $table->text('encontre_descricao')->nullable();
        $table->text('beneficios')->nullable();
        $table->string('nome_interno')->nullable();

        $table->string('valor_label_pf')->nullable();
        $table->string('valor_label_pj')->nullable();

        $table->string('valor_base_pf')->nullable();
        $table->string('valor_base_pj')->nullable();

        $table->text('carencias')->nullable();
      });

      $this->schema->create('plano_interesses', function ($table) {
        $table->increments('id');
        $table->string('nome');
        $table->string('email');
        $table->string('telefone');
        $table->string('cidade')->nullable();
        $table->string('plano_titulo')->nullable();

        $table->integer('plano_id')->unsigned()->index()->nullable();
        $table->foreign('plano_id')->references('id')->on('planos');

        $table->timestamps();
      });

    }
}
