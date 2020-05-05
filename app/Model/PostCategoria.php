<?php

namespace App\Model;

use App\Model\BaseModel;

class PostCategoria extends BaseModel
{

  protected $table = 'post_categorias';

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

  public static final function menu() {
    return static::where('status', 'ativo')->orderBy('ordem', 'asc')->get();
  }

  public static final function menuLimit5() {
    return static::where('status', 'ativo')->orderBy('ordem', 'asc')->limit(5)->get();    
  }
}
