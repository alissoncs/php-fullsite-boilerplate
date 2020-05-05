<?php

namespace App\Model;

use App\Model\BaseModel;

class MapeamentoEspecialidade extends BaseModel
{
  protected $table = 'mapeamento_especialidades';

  public $timestamps = false;

  protected $fillable = [
    'codigo_cbo',
    'descricao',
    'subespec',
    'tags_busca',
  ];
}
