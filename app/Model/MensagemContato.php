<?php

namespace App\Model;

use App\Model\BaseModel;

class MensagemContato extends BaseModel {
  protected $table = 'mensagens_contato';

  protected $fillable = [
    'nome',
    'assunto',
    'telefone',
    'email',
    'anexo',
    'mensagem',
    'status',
    'observacao_interna',
  ];

}
