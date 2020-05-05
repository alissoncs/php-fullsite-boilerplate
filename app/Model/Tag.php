<?php

namespace App\Model;

use App\Model\BaseModel;
use Illuminate\Database\Capsule\Manager as DB;

class Tag extends BaseModel
{

  protected $table = 'tags';

  protected $fillable = [
    'titulo',
    'slug',
  ];

  public function posts()
  {
    return $this->belongsToMany('App\Model\Post', 'posts_tags');
  }

  public static function listWithCountPosts()
  {
    $tags = static::withCount([
      'posts' => function ($query) {
        $query->where('status', '=', 'active');
      }]
    )->get();
    return $tags;
  }
}
