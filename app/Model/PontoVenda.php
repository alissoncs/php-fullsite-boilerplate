<?php

namespace App\Model;

use App\Model\BaseModel;

class PontoVenda extends BaseModel
{

  protected $table = 'pontos_venda';

  protected $fillable = [
    'titulo',
    'endereco_linha_1',
    'endereco_linha_2',
    'telefone',
    'horario_atendimento',
    'status',
    'ordem',
    'observacao',
  ];
}