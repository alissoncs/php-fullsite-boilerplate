<?php

namespace App\Model;

use App\Model\BaseModel;

class Banner extends BaseModel
{

  protected $table = 'banners';

  protected $fillable = [
    'titulo',
    'posicao',
    'sub_titulo',
    'resumo',
    'url',
    'nova_aba',
    'imagem',
    'imagem_mobile',
    'descricao',
    'btn_titulo',
    'btn_icon',
    'status',
    'ordem',
  ];

}
