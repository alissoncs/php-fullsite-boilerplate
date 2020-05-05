<?php

namespace App\Model;

use App\Model\BaseModel;

class FormSimulacaoPlano extends BaseModel
{

  protected $table = 'form_simulacao_plano';

  protected $fillable = [
    'nome',
    'telefone',
    'status',
    'email',
    'mensagem',
    'plano_titulo',
    'plano_id',
  ];
}
