<?php

namespace App\Model;

use App\Model\BaseModel;

class Unidade extends BaseModel
{

  protected $table = 'unidades';

  protected $fillable = [
    'status',
    'nome',
    'endereco_linha_1',
    'endereco_linha_2',
    'imagem',
    'telefone',
    'telefone_2',
    'link_maps',
    'html_maps',
    'lat',
    'lng',
    'horarios_exames',
    'ordem',
  ];
}
