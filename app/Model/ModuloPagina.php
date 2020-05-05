<?php

namespace App\Model;

use App\Model\BaseModel;

class ModuloPagina extends BaseModel
{

  protected $table = 'modulo_paginas';

  protected $fillable = [
    'titulo',
    'descricao',
    'status',
    'slug',
    'mostrar_menu',
    'mostrar_menu_footer',
    'conteudo',
    'habilitar_header',
    'habilitar_sidebar',
    'habilitar_sidebar_bottom',
    'modulo_pagina_id',
    'ordem',
  ];

  public function moduloPagina() {
    return $this->belongsTo('App\Model\ModuloPagina', 'modulo_pagina_id');
  }

  public function layout() {
    return $this->belongsTo('App\Model\PaginaCustomizada', 'pagina_customizada_id');
  }

  public function childs() {
    return $this->hasMany('App\Model\ModuloPagina', 'modulo_pagina_id');
  }

  public static final function treeById($id) {
    return static::with(['childs' => function($q) {
      $q->where('status', 'ativo');
    }, 'childs.childs' => function($q) {
      $q->where('status', 'ativo');
    }, 'childs.childs.childs' => function($q) {
      $q->where('status', 'ativo');
    }])->where('id', $id)->first();
  }

  public static final function menuFooter() {
    return static::with(['childs' => function($q) {
      $q->where('status', 'ativo')
      ->where('mostrar_menu_footer', 1)
      ->orderBy('ordem', 'asc');
    }])->where('mostrar_menu_footer', 1)
    ->where('modulo_pagina_id', null)
    ->orderBy('ordem', 'asc')
    // ->limit(10)
    ->get();
  }

  public static final function menuHeader() {
    return static::with(['childs' => function($q) {

      $q->where('status', 'ativo')
      ->where('mostrar_menu', 1)
      ->orderBy('ordem', 'asc');

    }, 'childs.childs' => function($q) {

      $q->where('status', 'ativo')
      ->where('mostrar_menu', 1)
      ->orderBy('ordem', 'asc');

    }])->where('mostrar_menu', 1)
      ->where('modulo_pagina_id', null)
      ->orderBy('ordem', 'asc')
      ->limit(100)
      ->get();
  }

  public static function levels() {
    return static::with(['childs', 'childs.childs', 'childs.childs.childs'])->where('modulo_pagina_id', null);
  }

  public function getUrlTreeBackward() {
    $pagina = $this;
    $level = $pagina;
    $url = '';

    while($level != null) {
      $url =  '/' . $level->slug . $url;
      if ($level->modulo_pagina_id !== null) {
        $level = static::find($level->modulo_pagina_id);
      } else {
        $level = null;
      }
    }

    return ltrim($url, '/');
  }

  public function save(array $options = []) {
    if (!empty($this->conteudo) && is_array($this->conteudo)) {
      $this->conteudo = json_encode($this->conteudo);
    }

    return parent::save($options); // returns boolean
  }

  public function toArray() {
    $data = parent::toArray();
    if (!empty($data['conteudo'])) {
      $data['conteudo'] = json_decode($data['conteudo']);
    }
    return $data;
  }

}
