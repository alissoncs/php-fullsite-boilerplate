<?php

namespace App\Model;

use App\Model\BaseModel;

class NoticiaTag extends BaseModel
{

  protected $table = 'noticias_tags';
  public $timestamps = false;

  protected $fillable = [
    'noticia_id',
    'tag_id',
  ];

}
