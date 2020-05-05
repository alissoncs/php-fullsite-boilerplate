<?php

namespace App\Model;

use App\Model\BaseModel;

class CustomPageModel extends BaseModel
{

  protected $table = 'custom_pages';

  protected $fillable = [
    'label',
    'status',
    'slug',
    'page_title',
    'page_description',
    'content',
    'content_builder',
    'custom_page_id',
  ];

  public function parent()
  {
    return $this->belongsTo('App\Model\CustomPageModel');
  }

  public function childs()
  {
    return $this->hasMany('App\Model\CustomPageModel');
  }


}
