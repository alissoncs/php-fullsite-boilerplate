<?php

namespace App\Model;

use App\Model\BaseModel;

class PaginaCustomizada extends BaseModel
{

  protected $table = 'paginas_customizadas';

  protected $fillable = [
    'titulo',
    'descricao',
    'status',
    'conteudo_builder',
  ];

}
