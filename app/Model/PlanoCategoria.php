<?php

namespace App\Model;

use App\Model\BaseModel;
use Illuminate\Database\Capsule\Manager as DB;


class PlanoCategoria extends BaseModel
{

  protected $table = 'plano_categorias';

  protected $fillable = [
    'titulo',
    'resumo',
    'conteudo',
    'status',
    'slug',

    'ordem',

    'beneficios_adicionais',

    'thumbnail',
    'thumbnail_mobile',

    'pagina_customizada_id',

    'background',
    'background_mobile',
    'arquivos',
  ];

  public function planos() {
    return $this->hasMany('App\Model\Plano', 'plano_categoria_id');
  }

  public static final function menu() {
    return static::with(['planos' => function($q) {
      $q->where('status', '=', 'ativo');
    }])->where('status', 'ativo')->orderBy('ordem', 'asc')->get();
  }

  public static final function encontreSeuPlano() {

    $output = DB::table('plano_categorias as pc')
    ->select('pc.id', 'pc.titulo', 'pc.resumo', 'pc.thumbnail', 'pc.slug')
    // ->distinct()
    ->join('planos as p', 'pc.id', '=', 'p.plano_categoria_id')
    ->where('pc.status', '=', 'ativo')
    ->where('p.encontre_exibir', '=', '1')
    ->orderBy('pc.ordem', 'ASC')
    ->groupBy('pc.id')
    ->get();

    return $output;
  }

  public function arquivos() {
    $data = $this->attributes['arquivos'];
    if (!empty($data)) {
      return json_decode($data);
    }
  }

  public function save(array $options = []) {
    if (is_object($this->attributes['arquivos']) || is_array($this->attributes['arquivos'])) {
      $this->attributes['arquivos'] = json_encode($this->attributes['arquivos']);
    }
    return parent::save($options);
  }

}
