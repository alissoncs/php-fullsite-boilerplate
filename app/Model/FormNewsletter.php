<?php

namespace App\Model;

use App\Model\BaseModel;

class FormNewsletter extends BaseModel
{
  protected $table = 'form_newsletter';

  protected $fillable = [
    'status', 'nome', 'email', 'telefone', 'observacoes',
  ];

}