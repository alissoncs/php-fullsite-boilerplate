<?php

namespace App\Model;

use App\Model\BaseModel;

class MenuAcessoRapido extends BaseModel
{
  protected $table = 'menu_acesso_rapido';

  protected $fillable = [
    'icone',
    'titulo',
    'link',
    'descricao',
    'nova_aba',
    'status',
    'ordem',
  ];
}
