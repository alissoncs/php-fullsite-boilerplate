<?php

namespace App\Model;

use App\Model\BaseModel;

class NoticiaCategoria extends BaseModel
{

  protected $table = 'noticia_categorias';

  protected $fillable = [
    'titulo',
    
    'resumo',
    'conteudo',

    'slug',
    'status',
    'thumbnail',
    'thumbnail_mobile',
    'ordem',
  ];

  public function noticias() {
    return $this->hasMany('App\Model\Noticia');
  }
}
