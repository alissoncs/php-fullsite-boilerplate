<?php

namespace App\Model;

use App\Model\BaseModel;

class Noticia extends BaseModel
{

  protected $table = 'noticias';

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

    'shares',
    'likes',
    'views',

    'noticia_categoria_id',
    'status',
  ];

  public function categoria() {
    return $this->belongsTo('App\Model\NoticiaCategoria', 'noticia_categoria_id');
  }
}
