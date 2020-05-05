<?php

namespace App\Model;

use App\Model\BaseModel;

class Post extends BaseModel
{

  protected $table = 'posts';

  protected $fillable = [
    'status',
    'slug',
    'destaque_home',
    'titulo',
    'descricao',
    'data',

    'thumbnail',
    'thumbnail_mobile',
    'thumbnail_detalhe',

    'conteudo',
    'conteudo_builder',

    'post_categoria_id',
    'post_categoria2_id',

    'shares',
    'likes',
    'views',

    'status',
  ];

  public function tags()
  {
    return $this->belongsToMany('App\Model\Tag', 'posts_tags');
  }

  public function categoria() {
    return $this->belongsTo('App\Model\PostCategoria', 'post_categoria_id');
  }
}
