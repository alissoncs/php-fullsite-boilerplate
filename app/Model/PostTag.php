<?php

namespace App\Model;

use App\Model\BaseModel;

class PostTag extends BaseModel
{

  protected $table = 'posts_tags';
  public $timestamps = false;

  protected $fillable = [
    'post_id',
    'tag_id',
  ];

}
