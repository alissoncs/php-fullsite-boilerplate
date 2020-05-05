<?php

namespace App\Model;

use App\Model\BaseModel;

class AcessoRapido extends BaseModel
{

  protected $table = 'menu_acesso_rapido';

  protected $fillable = [
    'icone',
    'titulo',
    'link',
    'descricao',
    'link',
    'nova_aba',
    'status',
    'ordem',
  ];

  public static final function menu() {
    return static::where('status', 'ativo')->orderBy('ordem', 'asc')->get();
  }
}