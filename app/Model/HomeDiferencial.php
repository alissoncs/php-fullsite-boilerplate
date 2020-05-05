<?php

namespace App\Model;

use App\Model\BaseModel;

class HomeDiferencial extends BaseModel
{

  protected $table = 'home_diferenciais';

  protected $fillable = [
    'imagem',
    'titulo',
    'btn_titulo',
    'descricao',
    'link',
    'nova_aba',
    'status',
    'ordem',
  ];
}
