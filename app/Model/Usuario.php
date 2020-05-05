<?php

namespace App\Model;

use App\Model\BaseModel;

class Usuario extends BaseModel
{

  protected $table = 'usuarios';

  protected $hidden = ['senha'];

  protected $fillable = [
    'email',
    'login',
    'senha',
    'ativo',
  ];

}
