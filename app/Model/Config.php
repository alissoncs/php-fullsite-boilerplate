<?php

namespace App\Model;

use App\Model\BaseModel;

class Config extends BaseModel {
  protected $table = 'configs';

  public static $DYNAMIC_KEYS = [
    'NOME_SITE',
    'URL_SITE',
    'HTTPS',
    'EMAIL',
    'FACEBOOK_URL',
    'TWITTER_URL',
    'INSTAGRAM_URL',
    'YOUTUBE_URL',
    'CACHE_ID',
  ];

  protected $fillable = [
    'key',
    'value',
    'type',
  ];

  public static function getMapped() {
    $keys = static::$DYNAMIC_KEYS;

    $dbData = static::all()->toArray();

    $result = [];

    foreach($keys as $key) {
      $found = null;

      foreach($dbData as $data) {
        if ($data['key'] === $key) {
          $found = $data['value'];
          break;
        }
      }

      $result[$key] = $found;
    }

    return $result;
  }

}
