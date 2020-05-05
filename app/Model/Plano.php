<?php

namespace App\Model;

use App\Model\BaseModel;

class Plano extends BaseModel
{

  protected $table = 'planos';

  protected $fillable = [
    'titulo',
    'conteudo',
    'status',
    'slug',
    'ordem',

    'cobertura',
    'rede',
    'acomodacao',
    'coparticipacao',

    'thumbnail',
    'thumbnail_mobile',

    'meta_title',
    'meta_description',

    'pagina_customizada_id',
    'plano_categoria_id',

    /// encontre
    'encontre_exibir',
    'encontre_exibir_ordem',
    'atencao_primaria',
    'encontre_descricao',
    'beneficios',
    'nome_interno',
    'valor_label_pf',
    // 'valor_label_pj',
    'valor_base_pf',
    // 'valor_base_pj',
    'carencias',
  ];



  public function decodeCarencias() {
    $car = $this->attributes['carencias'];
    return !empty($car) ? (array) json_decode($car) : [
      // 'urgencia_emergencia' => 'teste'
    ];
  }

  public function categoria() {
    return $this->belongsTo('App\Model\PlanoCategoria', 'plano_categoria_id');
  }

}
