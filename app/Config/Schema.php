<?php

namespace App\Config;

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Config\DatabaseConfig;

class Schema {
  public function __construct() {
    $this->dbConfig = new DatabaseConfig();
    $this->dbConfig->setup(null);
  }

  public function reset() {
    Capsule::schema()->dropIfExists('usuarios');
    Capsule::schema()->dropIfExists('configs');

    Capsule::schema()->dropIfExists('banners');
    Capsule::schema()->dropIfExists('unidades');

    Capsule::schema()->dropIfExists('planos');
    Capsule::schema()->dropIfExists('plano_categorias');

    Capsule::schema()->dropIfExists('mensagens_contato');
    Capsule::schema()->dropIfExists('form_simulacao_plano');
    Capsule::schema()->dropIfExists('form_newsletter');

    Capsule::schema()->dropIfExists('home_diferenciais');
    Capsule::schema()->dropIfExists('menu_acesso_rapido');

    Capsule::schema()->dropIfExists('pontos_venda');

    Capsule::schema()->dropIfExists('posts_tags');
    Capsule::schema()->dropIfExists('tags');

    Capsule::schema()->dropIfExists('noticias');
    Capsule::schema()->dropIfExists('noticia_categorias');

    Capsule::schema()->dropIfExists('posts');
    Capsule::schema()->dropIfExists('post_categorias');

    Capsule::schema()->dropIfExists('modulo_paginas');
    Capsule::schema()->dropIfExists('paginas_customizadas');
  }

  public function run() {

    Capsule::schema()->create('usuarios', function ($table) {
      $table->increments('id');
      $table->string('email')->unique();
      $table->string('login')->unique();
      $table->string('senha');
      $table->boolean('ativo');
      $table->timestamps();
    });

    Capsule::schema()->create('configs', function ($table) {
      $table->increments('id');
      $table->string('key')->unique();
      $table->string('value')->nullable();
      $table->string('type');
      $table->timestamps();
    });

    Capsule::schema()->create('menu_acesso_rapido', function ($table) {
      $table->increments('id');
      $table->string('icone')->nullable();
      $table->string('titulo');
      $table->string('link');
      $table->string('descricao');
      $table->boolean('nova_aba')->default(false);

      $table->string('status')->default('ativo');
      $table->integer('ordem')->default(0);

      $table->timestamps();
    });

    Capsule::schema()->create('home_diferenciais', function ($table) {
      $table->increments('id');
      $table->string('imagem')->nullable();
      $table->string('titulo');
      $table->string('btn_titulo')->nullable();
      $table->string('descricao')->nullable();
      $table->string('link');
      $table->boolean('nova_aba')->default(false);
      $table->string('status')->default('ativo');
      $table->integer('ordem')->default(0);

      $table->timestamps();
    });

    Capsule::schema()->create('banners', function ($table) {
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

    Capsule::schema()->create('paginas_customizadas', function ($table) {
      $table->increments('id');
      $table->string('titulo');
      $table->string('descricao')->nullable();
      $table->string('status');
      $table->text('conteudo_builder')->nullable();
      $table->timestamps();
    });

    Capsule::schema()->create('modulo_paginas', function ($table) {
      $table->increments('id');
      $table->string('titulo');
      $table->string('descricao')->nullable();
      $table->string('status')->default('ativo');
      $table->string('slug')->nullable();
      $table->text('conteudo')->nullable();

      $table->boolean('mostrar_menu')->default(true);
      $table->boolean('mostrar_menu_footer')->default(true);

      $table->integer('ordem')->default(0);

      $table->boolean('habilitar_header')->default(true);
      $table->boolean('habilitar_sidebar')->default(true);
      $table->boolean('habilitar_sidebar_bottom')->default(false);

      $table->integer('pagina_customizada_id')->unsigned()->index()->nullable();
      $table->foreign('pagina_customizada_id')->references('id')->on('paginas_customizadas');

      $table->integer('modulo_pagina_id')->unsigned()->index()->nullable();
      $table->foreign('modulo_pagina_id')->references('id')->on('modulo_paginas')->onDelete('cascade');

      $table->timestamps();
    });

    Capsule::schema()->create('pontos_venda', function ($table) {
      $table->increments('id');
      $table->string('titulo');
      $table->string('endereco_linha_1')->nullable();
      $table->string('endereco_linha_2')->nullable();
      $table->string('telefone')->nullable();
      $table->string('horario_atendimento')->nullable();
      $table->text('observacao')->nullable();
      $table->string('status');
      $table->integer('ordem')->default(0);

      $table->timestamps();
    });

    Capsule::schema()->create('post_categorias', function ($table) {
      $table->increments('id');

      $table->string('titulo');
      $table->text('resumo')->nullable();
      $table->text('conteudo')->nullable();
      $table->string('status');
      $table->string('slug')->unique();
      $table->integer('ordem')->default(0);

      $table->string('thumbnail')->nullable();
      $table->string('thumbnail_mobile')->nullable();

      $table->timestamps();
    });

    Capsule::schema()->create('tags', function ($table) {
      $table->increments('id');
      $table->string('titulo')->unique();
      $table->string('slug')->unique();
      $table->timestamps();
    });

    Capsule::schema()->create('noticia_categorias', function ($table) {
      $table->increments('id');

      $table->string('titulo');
      $table->text('resumo')->nullable();
      $table->text('conteudo')->nullable();
      $table->string('status');
      $table->string('slug')->unique();
      $table->integer('ordem')->default(0);

      $table->string('thumbnail')->nullable();
      $table->string('thumbnail_mobile')->nullable();

      $table->timestamps();
    });

    Capsule::schema()->create('noticias', function ($table) {
      $table->increments('id');
      $table->string('titulo');
      $table->string('slug')->unique();
      $table->string('descricao')->nullable();
      $table->string('status');
      $table->string('autor')->nullable();
      $table->string('autor2')->nullable();
      $table->string('autor3')->nullable();
      $table->boolean('destaque_home')->default(true);
      $table->string('thumbnail')->nullable();
      $table->string('thumbnail_mobile')->nullable();
      $table->datetime('data');
      $table->integer('likes')->default(0);
      $table->integer('shares')->default(0);
      $table->text('conteudo')->nullable();

      $table->integer('noticia_categoria_id')->unsigned()->index()->nullable();
      $table->foreign('noticia_categoria_id')->references('id')->on('noticia_categorias');

      $table->timestamps();
    });

    Capsule::schema()->create('posts', function ($table) {
      $table->increments('id');

      $table->string('titulo');
      $table->string('slug')->unique();
      $table->string('descricao')->nullable();

      $table->string('autor')->nullable();
      $table->string('autor2')->nullable();
      $table->string('autor3')->nullable();

      $table->string('status');

      $table->boolean('destaque_home');

      $table->string('thumbnail')->nullable();
      $table->string('thumbnail_mobile')->nullable();

      $table->datetime('data');

      $table->integer('likes')->default(0);
      $table->integer('shares')->default(0);

      $table->text('conteudo')->nullable();

      $table->integer('post_categoria_id')->unsigned()->index();
      $table->foreign('post_categoria_id')->references('id')->on('post_categorias');

      $table->integer('post_categoria2_id')->unsigned()->index()->nullable();
      $table->foreign('post_categoria2_id')->references('id')->on('post_categorias');

      $table->timestamps();
    });

    Capsule::schema()->create('posts_tags', function ($table) {
      $table->integer('post_id')->unsigned()->index();
      $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

      $table->integer('tag_id')->unsigned()->index();
      $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
    });


    Capsule::schema()->create('plano_categorias', function($table) {
      $table->increments('id');

      $table->string('titulo');

      $table->text('resumo')->nullable();
      $table->text('conteudo')->nullable();

      $table->string('status');
      $table->string('slug')->unique();

      $table->text('beneficios_adicionais')->nullable();

      $table->integer('ordem')->default(0);

      $table->string('thumbnail')->nullable();
      $table->string('thumbnail_mobile')->nullable();

      $table->string('background')->nullable();
      $table->string('background_mobile')->nullable();

      $table->integer('pagina_customizada_id')->unsigned()->index()->nullable();
      $table->foreign('pagina_customizada_id')->references('id')->on('paginas_customizadas');

      $table->timestamps();
    });

    Capsule::schema()->create('planos', function($table) {
      $table->increments('id');

      $table->string('titulo');

      $table->string('status');
      $table->string('slug')->unique();

      $table->text('conteudo')->nullable();

      $table->string('cobertura')->nullable();
      $table->string('rede')->nullable();
      $table->string('acomodacao')->nullable();
      $table->string('coparticipacao')->nullable();

      $table->string('meta_title')->nullable();
      $table->string('meta_description')->nullable();

      $table->string('thumbnail')->nullable();
      $table->string('thumbnail_mobile')->nullable();

      $table->integer('ordem')->default(0);

      $table->integer('pagina_customizada_id')->unsigned()->index()->nullable();
      $table->foreign('pagina_customizada_id')->references('id')->on('paginas_customizadas');

      $table->integer('plano_categoria_id')->unsigned()->index();
      $table->foreign('plano_categoria_id')->references('id')->on('plano_categorias');

      $table->timestamps();
    });


    Capsule::schema()->create('mensagens_contato', function ($table) {
      $table->increments('id');
      $table->string('nome');
      $table->string('assunto');
      $table->string('email');
      $table->string('telefone')->nullable();
      $table->string('anexo')->nullable();
      $table->text('mensagem')->nullable();
      $table->string('status');
      $table->text('observacao_interna')->nullable();

      $table->timestamps();
    });

    Capsule::schema()->create('form_simulacao_plano', function ($table) {
      $table->increments('id');
      $table->string('status');
      $table->string('nome');
      $table->string('email');
      $table->string('telefone')->nullable();
      $table->text('mensagem')->nullable();
      $table->string('plano_titulo')->nullable();
      $table->integer('plano_id')->nullable();
      $table->timestamps();
    });

    Capsule::schema()->create('form_newsletter', function ($table) {
      $table->increments('id');
      $table->string('status')->nullable();
      $table->string('nome')->nullable();
      $table->string('email');
      $table->string('telefone')->nullable();
      $table->string('observacoes')->nullable();
      $table->timestamps();
    });

    Capsule::schema()->create('unidades', function ($table) {
      $table->increments('id');
      $table->string('status')->nullable();
      $table->string('nome');
      $table->string('imagem')->nullable();
      $table->string('endereco_linha_1');
      $table->string('endereco_linha_2')->nullable();
      $table->string('telefone')->nullable();
      $table->string('telefone_2')->nullable();
      $table->string('link_maps')->nullable();
      $table->text('html_maps')->nullable();
      $table->string('lat')->nullable();
      $table->string('lng')->nullable();

      $table->text('horarios_exames')->nullable();
      $table->integer('ordem')->default(0);

      $table->timestamps();
    });


  }
}
