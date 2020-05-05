<?php

namespace App\Model;

use App\Model\BaseModel;

class PlanoInteresse extends BaseModel
{

  protected $table = 'plano_interesses';

  protected $fillable = [
    'nome',
    'telefone',
    'email',
    'cidade',
    'plano_titulo',
    'plano_whatsapp_click',
    'status',
  ];

  public static final function exists($data, $plano_titulo) {
    $plano = static::
    where('nome', @$data['nome'])
    ->where('email', @$data['email'])
    ->where('telefone', @$data['telefone'])
    ->where('plano_titulo', $plano_titulo)->first();

    return $plano;
  }
}
