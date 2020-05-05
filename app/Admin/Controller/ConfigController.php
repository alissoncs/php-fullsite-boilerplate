<?php
namespace App\Admin\Controller;

use App\Admin\Controller\BaseController;
use App\Model\Config;

class ConfigController extends BaseController
{
  public function list($request, $response, $args)
  {
    $keys = Config::$DYNAMIC_KEYS;

    $dbData = Config::all()->toArray();

    $result = [];

    foreach($keys as $key) {
      $found = null;

      foreach($dbData as $data) {
        if ($data['key'] === $key) {
          $found = $data['value'];
          break;
        }
      }

      $result[] = ['key' => $key, 'value' => $found];
    }
    return $response->withJson([
      'data' => $result,
    ]);
  }

  public function update($request, $response, $args) {
    $payload = $request->getParsedBody();

    $keys = Config::$DYNAMIC_KEYS;

    $updated = [];
    foreach ($payload as $key => $item) {
      if (in_array($key, $keys)) {
        $config = Config::where('key', $key)->first();
        if (!$config) {
          $config = new Config([
            'key' => $key,
            'type' => 'string',
          ]);
        }
        $config->value = empty($item) ? null : $item;
        $config->save();
        $updated[$key] = $item;
      }
    }

    return $response->withJson([
      'updated' => $updated,
    ]);
  }

}
